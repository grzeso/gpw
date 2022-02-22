<?php

namespace App\Service\Providers;

class ProviderFactory
{
    private GpwProvider $gpwProvider;

    public function __construct(GpwProvider $gpwProvider)
    {
        $this->gpwProvider = $gpwProvider;
    }

    public function getProvider($provider): ?AbstractProvider
    {
        if (GpwProvider::PROVIDER_NAME === $provider) {
            return $this->gpwProvider;
        }

        return null;
    }
}
