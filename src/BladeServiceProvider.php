<?php

namespace ABetter\Embed;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider {

    public function boot() {

        Blade::directive('style', function($expression){
			list($file,$vars,$link) = self::parseExpression($expression);
			$link = ($link) ? ',TRUE' : '';
			return "<?php echo \ABetter\Embed\Style::bladeStyle('{$file}',array_merge({$vars},get_defined_vars()){$link}); ?>";
        });

		Blade::directive('mixstyle', function($expression){
			list($file,$vars,$link) = self::parseExpression($expression);
			$link = ($link) ? ',TRUE' : '';
			return "<?php echo \ABetter\Embed\Style::bladeMixStyle('{$file}',array_merge({$vars},get_defined_vars()){$link}); ?>";
        });

        Blade::directive('script', function($expression){
			list($file,$vars,$link) = self::parseExpression($expression);
			$link = ($link) ? ',TRUE' : '';
			return "<?php echo \ABetter\Embed\Script::bladeScript('{$file}',array_merge({$vars},get_defined_vars()){$link}); ?>";
        });

		Blade::directive('mixscript', function($expression){
			list($file,$vars,$link) = self::parseExpression($expression);
			$link = ($link) ? ',TRUE' : '';
			return "<?php echo \ABetter\Embed\Script::bladeMixScript('{$file}',array_merge({$vars},get_defined_vars()){$link}); ?>";
        });

		// Components
		Blade::component('style', Style::class);
		Blade::component('script', Script::class);


    }

    public function register() {
        //
    }

	// ---

	protected static function parseExpression($parse) {
		$id = trim(strtok($parse,','));
		$vars = trim(str_replace($id,'',$parse),',');
		$vars = preg_replace('/(\'|") ?(=&gt;|=) ?(\'|")/',"$1 => $3",$vars);
		$end = trim(preg_match('/, ?(end|true|1)$/i',$parse));
		if ($end) $vars = trim(substr($vars,0,strrpos($vars,',')));
		$exp = array();
		$exp[0] = trim($id,'\'');
		$exp[1] = ($vars) ? $vars : '[]';
		$exp[2] = ($end) ? TRUE : FALSE;
		return $exp;
	}

}
