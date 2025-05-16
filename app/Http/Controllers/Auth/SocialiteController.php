<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialiteService;
use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    use ValidatesRequests;

    /**
     * The socialite service instance.
     *
     * @var SocialiteService
     */
    protected $socialiteService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SocialiteService $socialiteService)
    {
        $this->socialiteService = $socialiteService;
    }

    /**
     * Redirect the user to the provider authentication page.
     */
    public function loginSocial(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($request);

        try {
            return $this->socialiteService->getProviderRedirect($provider);
        } catch (Exception $e) {
            Log::error("Social login error: {$e->getMessage()}", [
                'provider' => $provider,
                'exception' => $e,
            ]);

            return redirect()->route('login')
                ->withErrors(['provider' => $e->getMessage()]);
        }
    }

    /**
     * Validate the provider parameter.
     */
    protected function validateProvider(Request $request): array
    {
        return $this->getValidationFactory()->make(
            $request->route()->parameters(),
            ['provider' => 'required|string|in:'.implode(',', $this->socialiteService->getSupportedProviders())]
        )->validate();
    }

    /**
     * Handle the provider callback and authenticate the user.
     */
    public function callbackSocial(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($request);

        try {
            $result = $this->socialiteService->handleProviderCallback($provider);
            $user = $result['user'];

            // Login user
            Auth::login($user, remember: true);

            return redirect()->intended(route('dashboard'));

        } catch (Exception $e) {
            Log::error("Social callback error: {$e->getMessage()}", [
                'provider' => $provider,
                'exception' => $e,
            ]);

            return redirect()->route('login')
                ->withErrors(['provider' => $e->getMessage()]);
        }
    }
}
