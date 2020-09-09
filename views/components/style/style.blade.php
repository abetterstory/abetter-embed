@php

use \ABetter\Embed\Embed;

$file = Embed::getFile($view ?? []);
$render = Embed::renderFile($file);

echo $render;

@endphp
