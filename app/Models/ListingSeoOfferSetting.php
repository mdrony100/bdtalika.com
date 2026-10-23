<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingSeoOfferSetting extends Model
{
    protected $fillable = [
        'message_template',
        'monthly_price',
        'yearly_price',
        'yearly_save',
        'contact_number',
        'status',
    ];

    /**
     * Default SEO offer message used when the settings row
     * is created for the first time. Fully editable from the
     * admin settings page afterwards.
     */
    public static function defaults(): array
    {
        return [
            'message_template' => "Dear {name},\n\n"
                . "Great news! Your business \"{listing}\" is one of the top-performing listings on BDTalika with {views}+ views.\n\n"
                . "Now take it to the next level with our Hero Slider Advertisement + Monthly SEO Support:\n\n"
                . "✅ Your business featured on the BDTalika homepage hero slider (top position)\n"
                . "✅ Keyword optimization for your listing\n"
                . "✅ Contact query form on your details page\n"
                . "✅ Google Business Profile (GBP) optimization\n"
                . "✅ Social sharing of your business\n\n"
                . "Pricing:\n"
                . "• Monthly — ৳{monthly_price}/month\n"
                . "• Yearly — ৳{yearly_price}/year (save ৳{save})\n\n"
                . "Interested? Just reply to this message.\n\n"
                . "Need help? WhatsApp/Call: {contact}\n\n"
                . "Thank you,\n"
                . "BDTalika Team",
            'monthly_price' => 1000,
            'yearly_price' => 7000,
            'yearly_save' => 3000,
            'contact_number' => '+8801738411242',
            'status' => 1,
        ];
    }

    private function fmt($price): string
    {
        $f = number_format((float) $price, 2, '.', '');
        $f = rtrim(rtrim($f, '0'), '.');
        return $f === '' ? '0' : $f;
    }

    /**
     * Build the copyable marketing message for a listing by
     * replacing the {placeholders} in the saved template.
     */
    public function buildMessage(Listing $listing, $views = null): string
    {
        return strtr($this->message_template, [
            '{name}' => optional($listing->user)->name ?: 'Customer',
            '{listing}' => $listing->name,
            '{views}' => number_format((int) ($views ?? 0)),
            '{monthly_price}' => $this->fmt($this->monthly_price),
            '{yearly_price}' => $this->fmt($this->yearly_price),
            '{save}' => $this->fmt($this->yearly_save),
            '{contact}' => $this->contact_number,
        ]);
    }
}
