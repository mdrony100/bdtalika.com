<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class SliderAnalytics extends Model
{
    use HasFactory;

    protected $table = 'slider_analytics';

    protected $fillable = [
        'slider_id',
        'event_type',
        'visitor_hash',
        'ip_address',
        'device',
        'browser',
        'operating_system',
        'event_date',
    ];

    public static function track(Slider $slider, string $eventType, ?Request $request = null): self
    {
        $request = $request ?: request();
        $agent = new Agent();
        $identity = auth()->check()
            ? 'user:' . auth()->id()
            : 'guest:' . $request->ip() . '|' . substr((string) $request->userAgent(), 0, 255);

        return self::create([
            'slider_id' => $slider->id,
            'event_type' => $eventType,
            'visitor_hash' => hash('sha256', $identity),
            'ip_address' => $request->ip(),
            'device' => $agent->device(),
            'browser' => $agent->browser(),
            'operating_system' => $agent->platform(),
            'event_date' => now()->toDateString(),
        ]);
    }

    public function slider()
    {
        return $this->belongsTo(Slider::class)->withDefault();
    }
}
