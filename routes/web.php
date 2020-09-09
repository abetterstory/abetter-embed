<?php

use Illuminate\Support\Facades\Route;

Route::get('/_browsersync/{event?}/{path}', 'ABetter\Embed\BrowsersyncController@handle')->where('path','.*');
