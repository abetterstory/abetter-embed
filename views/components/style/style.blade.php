@php

$var = (object) ['hello' => "world"];

_log($var,"After");
_log("Before",$var);

_log($var);

@endphp
<h1>STYLE</h1>
<p>{{ print_r($view ?? 'n/a') }}</p>
<p>{{ print_r($data ?? 'n/a') }}</p>
