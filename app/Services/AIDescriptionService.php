<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIDescriptionService
{
    public function generate($data)
    {
        $this->validateInput($data);

        $prompt = $this->buildPrompt($data);
        $text = $this->generateWithAI($prompt);
        $cleanText = $this->cleanResponse($text);

        $json = json_decode($cleanText, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            $description = $this->limitDescription($json['description'] ?? '', 4500);

            return [
                'title' => $json['title'] ?? '',
                'description' => $description,
                'meta_title' => $json['meta_title'] ?? '',
                'meta_keywords' => $json['meta_keywords'] ?? '',
                'meta_description' => $json['meta_description'] ?? '',
            ];
        }

        throw new \Exception('AI returned invalid JSON.');
    }

    private function generateWithAI($prompt)
    {
        $provider = strtolower((string) config('services.ai_provider', 'openai'));

        if ($provider === 'claude') {
            $apiKey = config('services.anthropic.key');

            if (empty($apiKey)) {
                throw new \Exception('Claude API key is missing.');
            }

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
                ->timeout(120)
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => config('services.anthropic.model', 'claude-haiku-4-5-20251001'),
                    'max_tokens' => 2200,
                    'temperature' => 0.7,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                $message = $response->json('error.message') ?: $response->body();
                throw new \Exception('Claude API request failed: ' . $message);
            }

            $responseData = $response->json();
            $text = $responseData['content'][0]['text'] ?? '';

            if (empty($text)) {
                throw new \Exception('Claude API returned an empty response.');
            }

            return $text;
        }

        if ($provider === 'openrouter') {
            $apiKey = config('services.openrouter.key');

            if (empty($apiKey)) {
                throw new \Exception('OpenRouter API key is missing.');
            }

            $headers = [
                'Content-Type' => 'application/json',
            ];

            $referer = config('services.openrouter.referer');
            if (!empty($referer)) {
                $headers['HTTP-Referer'] = $referer;
            }

            $title = config('services.openrouter.title');
            if (!empty($title)) {
                $headers['X-Title'] = $title;
            }

            $response = Http::withToken($apiKey)
                ->withHeaders($headers)
                ->acceptJson()
                ->timeout(120)
                ->post($this->openRouterChatCompletionsUrl(), [
                    'model' => config('services.openrouter.model', 'openai/gpt-4o-mini'),
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2200,
                ]);

            if (!$response->successful()) {
                $message = $response->json('error.message') ?: $response->body();
                throw new \Exception('OpenRouter API request failed: ' . $message);
            }

            $responseData = $response->json();
            $text = $responseData['choices'][0]['message']['content'] ?? '';

            if (empty($text)) {
                throw new \Exception('OpenRouter API returned an empty response.');
            }

            return $text;
        }

        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            throw new \Exception('OpenAI API key is missing.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2200,
            ]);

        if (!$response->successful()) {
            $message = $response->json('error.message') ?: $response->body();
            throw new \Exception('OpenAI API request failed: ' . $message);
        }

        $responseData = $response->json();
        $text = $responseData['choices'][0]['message']['content'] ?? '';

        if (empty($text)) {
            throw new \Exception('OpenAI API returned an empty response.');
        }

        return $text;
    }

    private function openRouterChatCompletionsUrl()
    {
        $baseUrl = rtrim((string) config('services.openrouter.base_url', 'https://openrouter.ai/api/v1'), '/');

        if (str_ends_with($baseUrl, '/chat/completions')) {
            return $baseUrl;
        }

        return $baseUrl . '/chat/completions';
    }

    private function validateInput($data)
    {
        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        if ($title === '') {
            throw new \Exception('Business title is required.');
        }

        if (mb_strlen($title) > 150) {
            throw new \Exception('Business title must not exceed 150 characters.');
        }
    }

    /**
     * Guarantee the description never exceeds the plain-text character limit
     * (measured the same way ListingRequest validates it: strip_tags + trim),
     * truncating on a tag-safe boundary instead of failing the whole request.
     */
    private function limitDescription($html, $limit = 4500)
    {
        $html = trim((string) $html);

        if (mb_strlen(trim(strip_tags($html))) <= $limit) {
            return $html;
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8"><div id="__ai_root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $root = $dom->getElementById('__ai_root');

        if (!$root) {
            // Fallback: no parseable HTML, hard-trim the plain text.
            return mb_substr($html, 0, $limit);
        }

        $remaining = $limit;
        $this->truncateNode($root, $remaining);

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        return trim($result);
    }

    /**
     * Recursively trims a DOM node's text content to fit the remaining budget,
     * dropping any nodes that fall past the limit and keeping tags balanced.
     */
    private function truncateNode(\DOMNode $node, &$remaining)
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($remaining <= 0) {
                $node->removeChild($child);
                continue;
            }

            if ($child->nodeType === XML_TEXT_NODE) {
                $length = mb_strlen($child->nodeValue);

                if ($length > $remaining) {
                    $child->nodeValue = mb_substr($child->nodeValue, 0, $remaining);
                    $remaining = 0;
                } else {
                    $remaining -= $length;
                }
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $this->truncateNode($child, $remaining);
            }
        }
    }

    private function cleanResponse($text)
    {
        $cleanText = trim((string) $text);
        $cleanText = str_replace('```json', '', $cleanText);
        $cleanText = str_replace('```', '', $cleanText);
        return trim($cleanText);
    }

    private function buildPrompt($data)
    {
        $title = $data['title'] ?? '';
        $type = $data['type'] ?? '';
        $category = $data['category'] ?? '';
        $division = $data['division'] ?? '';
        $city = $data['city'] ?? '';
        $description = $this->preparePromptDescription($data['description'] ?? '');

        return <<<PROMPT
You are a professional SEO content generator for a Bangladesh business directory website.

Your task is to generate structured SEO content for a business listing.

CRITICAL OUTPUT RULES:
- Return ONLY raw JSON.
- Do NOT include markdown.
- Do NOT include triple backticks.
- Do NOT include explanations.
- Do NOT include notes, examples, or extra text.
- The response MUST start with { and end with }.

JSON FORMAT:
{
  "title": "",
  "description": "",
  "meta_title": "",
  "meta_keywords": "",
  "meta_description": ""
}

DESCRIPTION RULES:
- Write 600-800 words.
- The description text (excluding HTML tags) MUST stay under 4300 characters. This is a hard limit.
- Use a professional, human-written, SEO-friendly style.
- Keep it natural, clear, and conversion focused.
- Avoid repetitive sentences and keyword stuffing.
- Do not invent awards, years of experience, certifications, branches, clients, or statistics.
- If information is unavailable, write generally.
- Mention the business name naturally 3-5 times.
- Mention the category, city, division, services, and related search keywords naturally.
- Use proper HTML formatting only with these tags:
  <h2>, <h3>, <p>, <ul>, <li>, <strong>
- Structure the content like this:
  <h2>Introduction</h2>
  <h2>About Business</h2>
  <h2>Products & Services</h2>
  <h2>Why Choose This Business</h2>
  <h2>Service Areas</h2>
  <h2>Customer Commitment</h2>

TITLE RULES:
- Rewrite the title.
- Make it SEO friendly, professional, and easy to read.
- Maximum 70 characters.

META TITLE RULES:
- Maximum 70 characters.
- Include business name, category, and city.

META KEYWORDS RULES:
- Use comma-separated keywords.
- Include business name, category, city, division, area, primary services, and related search terms.

META DESCRIPTION RULES:
- Maximum 160 characters.
- SEO friendly.
- Encourage clicks.

BUSINESS DATA:
Business Name: {$title}
Business Type: {$type}
Category: {$category}
Division: {$division}
City: {$city}
Short Description: {$description}

Now return only the raw JSON object.
PROMPT;
    }

    private function preparePromptDescription($description)
    {
        $description = trim(strip_tags((string) $description));
        $description = preg_replace('/\s+/', ' ', $description);

        if ($description === '') {
            return 'No existing description was provided. Generate the content using the business name, type, category, division, and city only.';
        }

        return mb_substr($description, 0, 1000);
    }
}
