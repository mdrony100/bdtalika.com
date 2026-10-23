<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class ListingAnalytics extends Model
{
    use HasFactory;

    protected $table = 'listing_analytics';

    protected $fillable = [
        'listing_id',
        'listing_owner_id',
        'user_id',
        'event_type',
        'visitor_hash',
        'ip_address',
        'device',
        'browser',
        'operating_system',
        'event_date',
    ];

    public static function track(Listing $listing, string $eventType, ?Request $request = null): self
    {
        $request = $request ?: request();
        $agent = new Agent();
        $identity = auth()->check()
            ? 'user:' . auth()->id()
            : 'guest:' . $request->ip() . '|' . substr((string) $request->userAgent(), 0, 255);

        return self::create([
            'listing_id' => $listing->id,
            'listing_owner_id' => $listing->user_id,
            'user_id' => auth()->id(),
            'event_type' => $eventType,
            'visitor_hash' => hash('sha256', $identity),
            'ip_address' => $request->ip(),
            'device' => $agent->device(),
            'browser' => $agent->browser(),
            'operating_system' => $agent->platform(),
            'event_date' => now()->toDateString(),
        ]);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
