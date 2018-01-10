<?php
declare(strict_types=1);

namespace Miaversa;

function copy_images()
{
	$src = CONTENT . "/images";
	$dst = OUTPUT . "/images";

	if (is_dir($dst)) {
		$command = "rm -rf {$dst}";
		exec($command);
	}

	$command = "cp -r {$src} {$dst}";
	exec($command);
}