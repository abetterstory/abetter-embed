<?php

namespace ABetter\Embed;

use Illuminate\View\Component;
use ABetter\Embed\Embed as EmbedComponent;

class Style extends EmbedComponent {

	public $type = 'scss';
	public $view = 'abetter-embed::components.style.style';

	public static function renderSlot($file) {
		return self::renderStyle($file);
	}

}
