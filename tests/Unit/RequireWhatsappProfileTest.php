<?php

namespace Tests\Unit;

use App\Http\Middleware\RequireWhatsappProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class RequireWhatsappProfileTest extends TestCase
{
    public function test_google_user_without_valid_phone_is_redirected_to_profile(): void
    {
        $request = Request::create('/user/create', 'GET');
        $request->setLaravelSession(app('session.store'));
        $request->setUserResolver(fn () => (object) [
            'social_login' => 'yes',
            'phone' => '',
            'whatsapp_opt_in' => false,
        ]);

        $response = (new RequireWhatsappProfile())->handle($request, fn () => new Response('ok'));

        $this->assertTrue($response->isRedirect(route('user.profile.index')));
        $this->assertSame($request->fullUrl(), session('url.intended'));
    }

    public function test_google_user_with_valid_phone_can_continue_without_consent(): void
    {
        $request = Request::create('/user/create', 'GET');
        $request->setLaravelSession(app('session.store'));
        $request->setUserResolver(fn () => (object) [
            'social_login' => 'yes',
            'phone' => '8801712345678',
            'whatsapp_opt_in' => false,
        ]);

        $response = (new RequireWhatsappProfile())->handle($request, fn () => new Response('ok'));

        $this->assertSame('ok', $response->getContent());
    }

    public function test_non_google_user_is_not_forced_by_this_middleware(): void
    {
        $request = Request::create('/user/create', 'GET');
        $request->setLaravelSession(app('session.store'));
        $request->setUserResolver(fn () => (object) [
            'social_login' => null,
            'phone' => '',
            'whatsapp_opt_in' => false,
        ]);

        $response = (new RequireWhatsappProfile())->handle($request, fn () => new Response('ok'));

        $this->assertSame('ok', $response->getContent());
    }
}
