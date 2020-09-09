<?php

namespace ABetter\Embed;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

    public function boot() {

		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');

		$this->loadViewsFrom(__DIR__.'/../views', 'abetter-embed');

		$this->loadViewComponentsAs('', [
			Embed::class,
	        Script::class,
	        Style::class,
	    ]);

    }

    public function register() {
		//
    }

}
