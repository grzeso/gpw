<?php

namespace App\Service\Providers;

class ProviderFactory
{
    private GpwProvider $gpwProvider;
    private MoneyProvider $moneyProvider;

    public function __construct(GpwProvider $gpwProvider, MoneyProvider $moneyProvider)
    {
        $this->gpwProvider = $gpwProvider;
        $this->moneyProvider = $moneyProvider;
    }

    public function getProvider($provider): ?AbstractProvider
    {
        if (GpwProvider::PROVIDER_NAME === $provider) {
            return $this->gpwProvider;
        }

        if (MoneyProvider::PROVIDER_NAME === $provider) {
            return $this->moneyProvider;
        }

        return null;
    }
}
