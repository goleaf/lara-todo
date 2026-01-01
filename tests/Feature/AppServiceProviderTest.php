<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Providers\AppServiceProvider;

class AppServiceProviderTest extends TestCase
{
    public function test_app_service_provider_registers_successfully()
    {
        $provider = new AppServiceProvider($this->app);

        $provider->register();
        $provider->boot();

        $this->assertTrue(true); // If no exception thrown, it's considered success
    }
}
