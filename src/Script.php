<?php

namespace ABetter\Embed;

use Illuminate\View\Component;
use ABetter\Embed\Embed as EmbedComponent;

class Script extends EmbedComponent {

	public $type = 'js';
	public $view = 'abetter-embed::components.script.script';

}
