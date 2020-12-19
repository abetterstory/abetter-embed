<?php

namespace ABetter\Embed;

use Illuminate\Routing\Controller as BaseController;

class BrowsersyncController extends BaseController {

	public function handle($event,$path) {

		$file = Embed::getFileFromPath($path);

		if ($file->ext == 'scss' || $file->ext == 'css') {
			Embed::renderStyle($file,TRUE);
			$this->data['message'] = "Updated style {$file->name}";
		} else if ($file->ext == 'js') {
			Embed::renderScript($file,TRUE);
			$this->data['message'] = "Updated script {$file->name}";
		} else {
			$this->data['error'] = "BrowsersyncService not available for type {$file->ext}";
		}

		return response()->json($this->data);

    }

}
