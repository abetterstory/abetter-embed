<?php

if (!function_exists('_console')) {

	function _console($var="",$tags=null) {
		if (!env('APP_DEBUG')) return;
		if (in_array(strtolower(env('APP_ENV')),['stage','production'])) return;
		if (!class_exists('\PhpConsole\Connector')) return;
		if (!empty($tags) && is_string($var) && !is_string($tags)) {
			$switch = $var; $var = $tags; $tags = $switch;
		}
		\PhpConsole\Connector::getInstance()->getDebugDispatcher()->dispatchDebug($var, $tags, 1);
	}

}
