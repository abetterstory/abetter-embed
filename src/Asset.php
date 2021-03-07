<?php

namespace ABetter\Embed;

use Illuminate\View\Component;
use ABetter\Embed\Embed as EmbedComponent;

class Asset extends EmbedComponent {

	public $type = '';
	public $view = 'abetter-embed::components.asset.asset';

}
