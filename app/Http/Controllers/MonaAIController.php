<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MonaAIController extends Controller
{
    public function chat(Request $request)
    {
        try {

            $message = trim($request->message);
            $maxPromptLength = (int) config('services.ai_max_prompt_length', 300);

            if(empty($message))
            {
                return response()->json([
                    'reply' => 'Please enter a message.'
                ]);
            }

            if(mb_strlen($message) > $maxPromptLength)
            {
                return response()->json([
                    'reply' => "Your message is too long. Please keep it under {$maxPromptLength} characters."
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | DIRECT BUSINESS NAME SEARCH
            |--------------------------------------------------------------------------
            */

            $directBusiness = Listing::where('status', 1)

                ->where(function($q) use ($message){

                    $q->where('name', 'LIKE', "%{$message}%");

                })

                ->latest()

                ->take(5)

                ->get();

            /*
            |--------------------------------------------------------------------------
            | IF BUSINESS FOUND
            |--------------------------------------------------------------------------
            */

            if($directBusiness->count() > 0)
            {
                $reply = "

                <div style='margin-bottom:20px;'>

                    🔍 <b>I found {$directBusiness->count()} matching businesses.</b>

                </div>

                ";

                $counter = 1;

                foreach($directBusiness as $item)
                {
                    $url = url('/details/' . $item->slug);

                    $reply .= "

                    <div style='margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #eee;'>

                        <div style='font-size:17px;font-weight:bold;margin-bottom:8px;line-height:1.5;'>

                            {$counter}. 🏢 {$item->name}

                        </div>

                    ";

                    if(!empty($item->real_address))
                    {
                        $reply .= "

                        <div style='margin-bottom:8px;line-height:1.7;'>

                            📍 ".Str::limit($item->real_address, 80)."

                        </div>

                        ";
                    }

                    if(!empty($item->description))
                    {
                        $reply .= "

                        <div style='margin-bottom:10px;line-height:1.8;color:#555;'>

                            📝 ".Str::limit(strip_tags($item->description), 120)."

                        </div>

                        ";
                    }

                    $reply .= "

                    <div style='display:flex;gap:10px;flex-wrap:wrap;'>

                        <a href='{$url}'
                        style='
                            display:inline-block;
                            padding:10px 16px;
                            background:#4f46e5;
                            color:#fff;
                            border-radius:10px;
                            text-decoration:none;
                            font-size:14px;
                            font-weight:500;
                        '>

                            View Details →

                        </a>

                    </div>

                    </div>

                    ";

                    $counter++;
                }

                return response()->json([
                    'reply' => $reply
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SHOW MORE SUPPORT
            |--------------------------------------------------------------------------
            */

            if(
                str_contains(strtolower($message), 'more') ||
                str_contains(strtolower($message), 'next') ||
                str_contains(strtolower($message), 'show more')
            )
            {
                $lastSearch = session('mona_last_search');

                if($lastSearch)
                {
                    return $this->loadMoreResults($lastSearch);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | AI INTENT PROMPT
            |--------------------------------------------------------------------------
            */

            $intentPrompt = "

You are Mona AI.

You help users discover businesses from a directory website.

Your job is ONLY:
- listing search
- business discovery
- category search
- area search
- city search

Examples:
- restaurant in banani
- hotel in cox's bazar
- gym in dhaka
- dentist in uttara
- women parlour in dhanmondi

RULES:

1. Detect business category.
2. Detect location/area/city.
3. Detect optional keyword.
4. If unrelated query:
   return:
   {
      \"type\": \"general_reply\"
   }

5. If business discovery query:
   return:
   {
      \"type\": \"listing_search\",
      \"category\": \"\",
      \"location\": \"\",
      \"keyword\": \"\"
   }

6. Return ONLY valid JSON.
7. Do not use markdown.
8. Do not use code block.
9. Do not explain anything.

User Message:
{$message}
";

            /*
            |--------------------------------------------------------------------------
            | AI INTENT DETECTION
            |--------------------------------------------------------------------------
            */

            $intentContent = $this->detectIntentWithAI($intentPrompt);

            /*
            |--------------------------------------------------------------------------
            | CLEAN AI RESPONSE
            |--------------------------------------------------------------------------
            */

            $intentContent = trim($intentContent);

            $intentContent = str_replace('```json', '', $intentContent);
            $intentContent = str_replace('```', '', $intentContent);

            $intentData = json_decode($intentContent, true);

            /*
            |--------------------------------------------------------------------------
            | INVALID RESPONSE
            |--------------------------------------------------------------------------
            */

            if(
                !$intentData ||
                !isset($intentData['type'])
            )
            {
                return response()->json([
                    'reply' => '
                    ❌ AI response parsing failed.
                    '
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | GENERAL REPLY
            |--------------------------------------------------------------------------
            */

            if($intentData['type'] == 'general_reply')
            {
                return response()->json([
                    'reply' => '

                    <div style="line-height:1.8;">

                        👋 Hi, I am <b>Mona AI Assistant</b>.

                        <br><br>

                        I can help you discover businesses by category and location.

                        <br><br>

                        ✅ Examples:

                        <br><br>

                        🍽️ Restaurant in Banani<br>
                        🏥 Hospital in Mirpur<br>
                        💇 Women parlour in Dhaka<br>
                        🏨 Hotel in Coxs Bazar<br>
                        🦷 Dentist in Uttara

                    </div>

                    '
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SEARCH DATA
            |--------------------------------------------------------------------------
            */

            $category = $intentData['category'] ?? '';
            $location = $intentData['location'] ?? '';
            $keyword = $intentData['keyword'] ?? '';

            /*
            |--------------------------------------------------------------------------
            | EMPTY QUERY
            |--------------------------------------------------------------------------
            */

            if(
                empty($category) &&
                empty($location) &&
                empty($keyword)
            )
            {
                return response()->json([
                    'reply' => '
                    ⚠️ Please search using business category or location.
                    '
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SEARCH QUERY
            |--------------------------------------------------------------------------
            */

            //$query = Listing::query();
            $query = Listing::with('category');
            $query->where('status', 1);

            /*
            |--------------------------------------------------------------------------
            | CATEGORY SEARCH
            |--------------------------------------------------------------------------
            */

            // if(!empty($category))
            // {
            //     $query->where(function($q) use ($category){

            //         $q->where('name', 'LIKE', "%{$category}%")
            //             ->orWhere('description', 'LIKE', "%{$category}%");

            //     });
            // }

            if(!empty($category))
            {
                $query->whereHas('category', function($q) use ($category){

                    $q->where('title', 'LIKE', "%{$category}%");

                });
            }

            /*
            |--------------------------------------------------------------------------
            | LOCATION SEARCH
            |--------------------------------------------------------------------------
            */

            if(!empty($location))
            {
                $query->where('real_address', 'LIKE', "%{$location}%");
            }

            /*
            |--------------------------------------------------------------------------
            | KEYWORD SEARCH
            |--------------------------------------------------------------------------
            */

            if(!empty($keyword))
            {
                $query->where(function($q) use ($keyword){

                    $q->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%");

                });
            }

            /*
            |--------------------------------------------------------------------------
            | TOTAL COUNT
            |--------------------------------------------------------------------------
            */

            $totalCount = $query->count();

            /*
            |--------------------------------------------------------------------------
            | GET FIRST 5
            |--------------------------------------------------------------------------
            */

            $listing = $query
                ->latest()
                ->take(5)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | NO RESULTS
            |--------------------------------------------------------------------------
            */

            if($listing->count() == 0)
            {
                return response()->json([
                    'reply' => "
                    ❌ Sorry, I couldn't find any matching businesses.
                    "
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE SESSION
            |--------------------------------------------------------------------------
            */

            session([
                'mona_last_search' => [
                    'category' => $category,
                    'location' => $location,
                    'keyword' => $keyword,
                    'offset' => 5
                ]
            ]);

            /*
            |--------------------------------------------------------------------------
            | SEARCH URL
            |--------------------------------------------------------------------------
            */

            $searchKeyword = trim($category . ' in ' . $location);

            $searchUrl = url('/listing/bangladesh?keywords=' . urlencode($searchKeyword));

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            $reply = "

            <div style='margin-bottom:20px;'>

                📋 <b>I found {$totalCount} businesses.</b>

            </div>

            ";

            /*
            |--------------------------------------------------------------------------
            | TOP 10 MESSAGE
            |--------------------------------------------------------------------------
            */

            if($totalCount > 10)
            {
                $reply .= "

                <div style='margin-bottom:20px;line-height:1.7;'>

                    ✨ Here are the top 10 businesses.

                </div>

                ";
            }

            $counter = 1;

            foreach($listing as $item)
            {
                $url = url('/details/' . $item->slug);

                $reply .= "

                <div style='margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #eee;'>

                    <div style='font-size:17px;font-weight:bold;margin-bottom:8px;line-height:1.5;'>
                        {$counter}. 🏢 {$item->name}
                    </div>

                ";

                if(!empty($item->real_address))
                {
                    $reply .= "

                    <div style='margin-bottom:8px;line-height:1.7;'>

                        📍 ".Str::limit($item->real_address, 80)."

                    </div>

                    ";
                }

                if(!empty($item->description))
                {
                    $reply .= "

                    <div style='margin-bottom:10px;line-height:1.8;color:#555;'>

                        📝 ".Str::limit(strip_tags($item->description), 120)."

                    </div>

                    ";
                }

                $reply .= "

                <div style='display:flex;gap:10px;flex-wrap:wrap;'>

                    <a href='{$url}'
                    style='
                        display:inline-block;
                        padding:10px 16px;
                        background:#4f46e5;
                        color:#fff;
                        border-radius:10px;
                        text-decoration:none;
                        font-size:14px;
                        font-weight:500;
                    '>

                        View Details →

                    </a>

                </div>

                </div>

                ";

                $counter++;
            }

            /*
            |--------------------------------------------------------------------------
            | SHOW MORE BUTTON
            |--------------------------------------------------------------------------
            */

            if($totalCount > 5)
            {
                $reply .= "

                <div style='margin-top:20px;display:flex;gap:10px;flex-wrap:wrap;'>

                    <button
                        onclick=\"setSuggestion('show more')\"
                        style='
                            border:none;
                            background:#111827;
                            color:#fff;
                            padding:12px 18px;
                            border-radius:10px;
                            cursor:pointer;
                            font-size:14px;
                            font-weight:500;
                        '
                    >
                        Show More Results
                    </button>

                    <a href='{$searchUrl}'
                    style='
                        display:inline-block;
                        background:#4f46e5;
                        color:#fff;
                        padding:12px 18px;
                        border-radius:10px;
                        text-decoration:none;
                        font-size:14px;
                        font-weight:500;
                    '>

                        View All →

                    </a>

                </div>

                ";
            }

            return response()->json([
                'reply' => $reply
            ]);

        }
        catch (\Exception $e)
        {
            return response()->json([
                'reply' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD MORE RESULTS
    |--------------------------------------------------------------------------
    */

    private function loadMoreResults($search)
    {
        $query = Listing::query();

        $query->where('status', 1);

        // if(!empty($search['category']))
        // {
        //     $query->where(function($q) use ($search){

        //         $q->where('name', 'LIKE', "%{$search['category']}%")
        //             ->orWhere('description', 'LIKE', "%{$search['category']}%");

        //     });
        // }

        if(!empty($search['category']))
            {
                $query->whereHas('category', function($q) use ($search){

                    $q->where('title', 'LIKE', "%{$search['category']}%");

                });
            }

        if(!empty($search['location']))
        {
            $query->where('real_address', 'LIKE', "%{$search['location']}%");
        }

        if(!empty($search['keyword']))
        {
            $query->where(function($q) use ($search){

                $q->where('name', 'LIKE', "%{$search['keyword']}%")
                    ->orWhere('description', 'LIKE', "%{$search['keyword']}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL COUNT
        |--------------------------------------------------------------------------
        */

        $totalCount = $query->count();

        $offset = $search['offset'];

        /*
        |--------------------------------------------------------------------------
        | LOAD NEXT 5
        |--------------------------------------------------------------------------
        */

        $listing = $query
            ->skip($offset)
            ->take(5)
            ->get();

        if($listing->count() == 0)
        {
            return response()->json([
                'reply' => '
                ✅ No more businesses found.
                '
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE OFFSET
        |--------------------------------------------------------------------------
        */

        session([
            'mona_last_search.offset' => $offset + 5
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH URL
        |--------------------------------------------------------------------------
        */

        $searchKeyword = trim($search['category'] . ' in ' . $search['location']);

        $searchUrl = url('/listing/bangladesh?keywords=' . urlencode($searchKeyword));

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        $reply = '';

        $counter = $offset + 1;

        foreach($listing as $item)
        {
            $url = url('/details/' . $item->slug);

            $reply .= "

            <div style='margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #eee;'>

                <div style='font-size:17px;font-weight:bold;margin-bottom:8px;line-height:1.5;'>
                    {$counter}. 🏢 {$item->name}
                </div>

            ";

            if(!empty($item->real_address))
            {
                $reply .= "

                <div style='margin-bottom:8px;line-height:1.7;'>

                    📍 ".Str::limit($item->real_address, 80)."

                </div>

                ";
            }

            if(!empty($item->description))
            {
                $reply .= "

                <div style='margin-bottom:10px;line-height:1.8;color:#555;'>

                    📝 ".Str::limit(strip_tags($item->description), 120)."

                </div>

                ";
            }

            $reply .= "

            <div style='display:flex;gap:10px;flex-wrap:wrap;'>

                <a href='{$url}' target='_blank'
                style='
                    display:inline-block;
                    padding:10px 16px;
                    background:#4f46e5;
                    color:#fff;
                    border-radius:10px;
                    text-decoration:none;
                    font-size:14px;
                    font-weight:500;
                '>

                    View Details →

                </a>

            </div>

            </div>

            ";

            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | BUTTONS
        |--------------------------------------------------------------------------
        */

        if(($offset + 5) < $totalCount)
        {
            $reply .= "

            <div style='margin-top:20px;display:flex;gap:10px;flex-wrap:wrap;'>

                <button
                    onclick=\"setSuggestion('show more')\"
                    style='
                        border:none;
                        background:#111827;
                        color:#fff;
                        padding:12px 18px;
                        border-radius:10px;
                        cursor:pointer;
                        font-size:14px;
                        font-weight:500;
                    '
                >
                    Show More Results
                </button>

                <a href='{$searchUrl}'
                target='_blank'
                style='
                    display:inline-block;
                    background:#4f46e5;
                    color:#fff;
                    padding:12px 18px;
                    border-radius:10px;
                    text-decoration:none;
                    font-size:14px;
                    font-weight:500;
                '>

                    View All →

                </a>

            </div>

            ";
        }

        return response()->json([
            'reply' => $reply
        ]);
    }

    private function detectIntentWithAI($intentPrompt)
    {
        $provider = strtolower((string) config('services.ai_provider', 'openai'));

        if($provider === 'claude')
        {
            $apiKey = config('services.anthropic.key');

            if(empty($apiKey))
            {
                throw new \Exception('Claude API key is missing. Please set ANTHROPIC_API_KEY in your environment.');
            }

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => config('services.anthropic.model', 'claude-haiku-4-5-20251001'),
                'max_tokens' => 300,
                'temperature' => 0.1,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $intentPrompt
                    ]
                ]
            ]);

            if(!$response->successful())
            {
                $message = $response->json('error.message') ?: $response->body();
                throw new \Exception('Claude API request failed. ' . $message);
            }

            $responseData = $response->json();
            $intentContent = $responseData['content'][0]['text'] ?? '';

            if(empty($intentContent))
            {
                throw new \Exception('Claude API returned an empty response.');
            }

            return $intentContent;
        }

        if($provider === 'openrouter')
        {
            $apiKey = config('services.openrouter.key');

            if(empty($apiKey))
            {
                throw new \Exception('OpenRouter API key is missing. Please set OPENROUTER_API_KEY in your environment.');
            }

            $headers = [
                'Content-Type' => 'application/json',
            ];

            $referer = config('services.openrouter.referer');
            if(!empty($referer))
            {
                $headers['HTTP-Referer'] = $referer;
            }

            $title = config('services.openrouter.title');
            if(!empty($title))
            {
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
                            'content' => $intentPrompt
                        ]
                    ],
                    'temperature' => 0.1,
                    'max_tokens' => 300
                ]);

            if(!$response->successful())
            {
                $message = $response->json('error.message') ?: $response->body();
                throw new \Exception('OpenRouter API request failed. ' . $message);
            }

            $responseData = $response->json();
            $intentContent = $responseData['choices'][0]['message']['content'] ?? '';

            if(empty($intentContent))
            {
                throw new \Exception('OpenRouter API returned an empty response.');
            }

            return $intentContent;
        }

        $intentResult = OpenAI::chat()->create([

            'model' => config('services.openai.model', 'gpt-4o-mini'),

            'messages' => [
                [
                    'role' => 'user',
                    'content' => $intentPrompt
                ]
            ],

            'temperature' => 0.1

        ]);

        return $intentResult->choices[0]->message->content;
    }

    private function openRouterChatCompletionsUrl()
    {
        $baseUrl = rtrim((string) config('services.openrouter.base_url', 'https://openrouter.ai/api/v1'), '/');

        if (str_ends_with($baseUrl, '/chat/completions')) {
            return $baseUrl;
        }

        return $baseUrl . '/chat/completions';
    }
}
