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

		$this->loadViewsFrom(__DIR__.'/../views', 'abetter-embed');

		$this->loadViewComponentsAs('', [
	        Script::class,
	        Style::class,
	    ]);

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
