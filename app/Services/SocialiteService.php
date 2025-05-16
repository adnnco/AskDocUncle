<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteService
{
    /**
     * The supported social providers.
     *
     * @var array
     */
    protected $providers = ['facebook', 'google', 'github'];

    /**
     * Get the list of supported providers.
     */
    public function getSupportedProviders(): array
    {
        return $this->providers;
    }

    /**
     * Check if the provider is supported.
     */
    public function isProviderSupported(string $provider): bool
    {
        return in_array($provider, $this->providers);
    }

    /**
     * Check if the provider is configured in services config.
     */
    public function isProviderConfigured(string $provider): bool
    {
        return Config::has("services.{$provider}.client_id") &&
               Config::has("services.{$provider}.client_secret") &&
               Config::has("services.{$provider}.redirect");
    }

    /**
     * Get the redirect for the provider.
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getProviderRedirect(string $provider)
    {
        if (! $this->isProviderSupported($provider)) {
            throw new Exception("Unsupported provider: {$provider}");
        }

        if (! $this->isProviderConfigured($provider)) {
            throw new Exception("Provider not configured: {$provider}");
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the provider callback and get or create the user.
     *
     * @throws Exception
     */
    public function handleProviderCallback(string $provider): array
    {
        if (! $this->isProviderSupported($provider)) {
            throw new Exception("Unsupported provider: {$provider}");
        }

        if (! $this->isProviderConfigured($provider)) {
            throw new Exception("Provider not configured: {$provider}");
        }

        $providerUser = Socialite::driver($provider)->user();

        // Check if user email exists
        if (empty($providerUser->getEmail())) {
            throw new Exception("Unable to retrieve email from {$provider}");
        }

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $providerUser->getEmail()],
            [
                'name' => $providerUser->getName() ?? $providerUser->getNickname() ?? $providerUser->getEmail(),
                'password' => Str::password(),
            ]
        );

        // Check if user is trying to login with a different provider
        if ($user->provider && $user->provider !== $provider) {
            throw new Exception("This email is already associated with a different provider ({$user->provider})");
        }

        // Update user with provider data
        $user->update([
            'provider' => $provider,
            'provider_id' => $providerUser->getId(),
            'provider_refresh_token' => $providerUser->refreshToken ?? null,
        ]);

        // If user was just created, fire registered event
        $isNewUser = $user->wasRecentlyCreated;
        if ($isNewUser) {
            event(new Registered($user));
        }

        return [
            'user' => $user,
            'is_new_user' => $isNewUser,
        ];
    }
}
