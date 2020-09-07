<?php

namespace ABetter\Embed;

use Illuminate\View\Component;

class Script extends Component {

	public $path;

	public function __construct($path=NULL) {
		$this->path = $path;
    }

    public function render() {
		return function(array $data) {
			return view('abetter-embed::components.script.script')->with('data',$data)->render();
    	};
    }

}
