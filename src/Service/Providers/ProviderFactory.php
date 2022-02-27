<?php

namespace App\Service\Providers;

class ProviderFactory
{
    private iterable $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function getProvider($appProvider): ?AbstractProvider
    {
        /** @var AbstractProvider $provider */
        foreach ($this->providers as $provider) {
            if ($provider->check($appProvider)) {
                return $provider;
            }
        }

        return null;
    }
}
