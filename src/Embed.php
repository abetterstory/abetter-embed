<?php

namespace ABetter\Embed;

use Leafo\ScssPhp\Compiler;
use Patchwork\JSqueeze;

use Illuminate\View\Component;

class Embed extends Component {

	public $type;
	public $view = 'abetter-embed::components.embed.embed';

	public static $files = [];
	public static $embedded = [];

	// ---

	public function __construct() {
		//
    }

    public function render() {
		return function(array $data) {
			return view($this->view)->with([
				'type' => $this->type,
				'data' => $data,
			])->render();
    	};
    }

	// ---

	public static function getFile($views=[]) {
		$file = new \StdClass();
		$file->views = array_reverse($views??[]);
		$file->first = reset($views);
		$file->last = end($views);
		$file->current = NULL;
		$file->name = "";
		foreach ($file->views AS $i => $view) {
			if (!empty($file->current)) continue;
			if (in_array($view['file'],['embed.blade.php','script.blade.php','style.blade.php'])) continue;
			$file->current = $view;
		}
		$file->attr = "";
		$file->link = _boolean($file->last['data']['data']['attributes']['link'] ?? FALSE);
		$file->defer = _boolean($file->last['data']['data']['attributes']['defer'] ?? FALSE);
		$file->async = _boolean($file->last['data']['data']['attributes']['async'] ?? FALSE);
		$file->slot = $file->last['data']['data']['slot'] ?? NULL;
		$file->src = $file->last['data']['data']['attributes']['src'] ?? "";
		$file->path = realpath($file->current['path'] ?? '');
		$file->base = basename($file->src);
		$file->name = $file->path.'/'.$file->src;
		$file->ext = pathinfo($file->src, PATHINFO_EXTENSION);
		$file->type = self::getType($file->ext);
		if (preg_match('/^\~/',$file->src)) {
			$file->name = base_path().str_replace(['~/','~'],['/','/'],$file->src);
		} else if (preg_match('/^\//',$file->src)) {
			$file->name = resource_path('views').$file->src;
		}
		if (preg_match('/\.\./',$file->name)) {
			$file->name = realpath($file->name);
		}
		$file->is = is_file($file->name);
		self::$files[$file->name] = $file;
		return $file;
	}

	public static function getFileFromPath($name,$path=NULL) {
		$file = new \StdClass();
		$file->name = (($path)?$path:base_path()).'/'.$name;
		$file->path = dirname($file->name);
		$file->base = basename($file->name);
		$file->ext = pathinfo($file->name, PATHINFO_EXTENSION);
		$file->type = self::getType($file->ext);
		$file->link = FALSE;
		$file->attr = "";
		return $file;
	}

	public static function getType($ext) {
		$types = [
			'js' => 'script',
			'css' => 'style',
			'scss' => 'style',
		];
		return $types[$ext] ?? "";
	}

	// ---

	public static function renderFile($file) {
		$file = (is_string($file)) ? (self::$files[$file] ?? NULL) : $file;
		if (empty($file->name)) return;
		if (isset(self::$embedded[$file->name])) {
			return "<!--skip:{$file->name}-->";
		} else if (empty($file->is)) {
			return "<!--missing:{$file->name}-->";
		} else if ($file->type == 'script') {
			return self::renderScript($file);
		} else if ($file->type == 'style') {
			return self::renderStyle($file);
		}
	}

	// ---

	public static function bladeScript($file,$vars=[],$link=NULL) {
		$path = $vars['view'][count($vars['view'])-1]['path'] ?? "";
		$file = self::getFileFromPath($file,$path);
		$file->link = ($link !== NULL) ? $link : $file->link;
		return Embed::renderScript($file);
	}

	public static function renderScript($file) {
		$JSqueeze = new JSqueeze();
		// ---
		$file->source = (is_file($file->name)) ? trim(file_get_contents($file->name)) : "";
		$file->includes = self::parseScriptIncludes($file->source,$file);
		if (!empty($file->slot)) $file->includes .= PHP_EOL.(string)$file->slot;
		$file->render = $JSqueeze->squeeze($file->includes,TRUE,TRUE,FALSE);
		$file->link = (env('APP_ENV') == 'sandbox') ? TRUE : $file->link;
		if ($file->link) {
			if (!empty($file->async)) $file->attr .= ' async';
			if (!empty($file->defer)) $file->attr .= ' defer';
			$file->public = '/scripts/components/'.str_replace($file->ext,'js',$file->base);
			$file->location = public_path().$file->public;
			if (!is_dir(dirname($file->location))) mkdir(dirname($file->location),0777,TRUE);
			@file_put_contents($file->location,$file->render);
			@chmod($file->location,0755);
			$file->return = "<script src=\"{$file->public}\" type=\"text/javascript\" {$file->attr}></script>";
		} else {
			$file->return = "<script>{$file->render}</script>";
		}
		self::$embedded[$file->name] = TRUE;
		return $file->return;
	}

	public static function parseScriptIncludes($source,$file) {
		$source = preg_replace_callback('/\@include\(([^\)]+)\);?/',function($matches) use ($file){
			$include = trim($matches[1],'\'\"');
			if (preg_match('/^\~/',$include)) {
				$include = base_path().'/'.trim($include,'~');
			} else if (preg_match('/node_modules/',$include)) {
				$include = base_path().'/node_modules/'.str_replace(['/node_modules/','node_modules/'],['',''],$include);
			} else if (preg_match('/^\//',$include)) {
				$include = base_path().$include;
			} else {
				$include = ($file->path ?? base_path()) . '/'.$include;
			}
			return (is_file($include)) ? file_get_contents($include) : "";
		},$source);
		return $source;
	}

	// ---

	public static function bladeStyle($file,$vars=[],$link=NULL) {
		$path = $vars['view'][count($vars['view'])-1]['path'] ?? "";
		$file = self::getFileFromPath($file,$path);
		$file->link = ($link !== NULL) ? $link : $file->link;
		return Embed::renderStyle($file);
	}

	public static function renderStyle($file) {
		$Scss = new Compiler();
		$Scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
		$Scss->setImportPaths([
			$file->path,
			resource_path('styles'),
			resource_path('sass'),
			resource_path('css'),
			base_path().'/node_modules',
		]);
		// ---
		$file->source = (is_file($file->name)) ? trim(file_get_contents($file->name)) : "";
		$file->includes = self::parseStyleIncludes($file->source,$file);
		if (!empty($file->slot)) $file->includes .= PHP_EOL.(string)$file->slot;
		$file->render = $Scss->compile($file->includes);
		$file->link = (env('APP_ENV') == 'sandbox') ? TRUE : $file->link;
		if ($file->link) {
			$file->public = '/styles/components/'.str_replace($file->ext,'css',$file->base);
			$file->location = public_path().$file->public;
			if (!is_dir(dirname($file->location))) mkdir(dirname($file->location),0777,TRUE);
			@file_put_contents($file->location,$file->render);
			@chmod($file->location,0755);
			$file->return = "<link href=\"{$file->public}\" rel=\"stylesheet\" type=\"text/css\" {$file->attr}>";
		} else {
			$file->return = "<style>{$file->render}</style>";
		}
		self::$embedded[$file->name] = TRUE;
		return $file->return;
	}

	public static function parseStyleIncludes($source,$file) {
		// Handled by Sass @import/@include setImportPaths
		/*
		$source = preg_replace_callback('/\@include([^\;]+);/',function($matches) use ($file){
			$include = trim($matches[1],'\'\" ');
			if (preg_match('/^\~/',$include)) {
				$include = base_path().'/node_modules/'.trim($include,'~');
			} else if (preg_match('/^\//',$include)) {
				$include = base_path().$include;
			} else {
				$include = ($file->path ?? base_path()) . '/'.$include;
			}
			_log($include);
			return (is_file($include)) ? file_get_contents($include) : "";
		},$source);
		*/
		return $source;
	}

}
