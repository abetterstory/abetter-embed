<?php

namespace ABetter\Embed;

use Illuminate\View\Component;

class Style extends Component {

	public $path;

    public function __construct($path=NULL) {
		$this->path = $path;
    }

    public function render() {
		return function(array $data) {
			return view('abetter-embed::components.style.style')->with('data',$data)->render();
    	};
    }

}
