<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Type;

function types() : array
{
	$types = [];
	$file = CONTENT . '/site.json';
	$tts = fjson($file);
	$tts = $tts['types'];
	foreach($tts as $t){
		$types[] = new Type($t['slug'], $t['name'], $t['description']);
	}

	return $types;
}