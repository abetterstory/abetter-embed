<?php

namespace ABetter\Embed;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {

		_console("HELLO");

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
		//
    }

}
