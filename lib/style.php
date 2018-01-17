<?php
declare(strict_types=1);

namespace Miaversa;

function parse_styles() {
	$files = [
		CONTENT . '/css/tacbase.css',
		CONTENT . '/css/tacmin.css',
		CONTENT . '/css/tacmid.css',
		CONTENT . '/css/tacmax.css',
	];

	$parts = [];

	foreach($files as $file)
	{
		$b = basename($file, '.css');
		$parts[$b] = [];

		$lines = file($file);
		foreach($lines as $line)
		{
			$line = trim($line);
			if (substr($line, 0, 1) != '.') {
				print "error in {$file}." . PHP_EOL;
				exit();
			}
			if ('' == $line) {
				print 'err' . PHP_EOL;
				exit();
			}

			$pos = strpos($line, '{');
			$name =  substr($line, 0, $pos);
			$name = trim($name);

			if(strpos($name, ' ') > 0) {
				$pos = strpos($line, ' ');
				$name =  substr($line, 0, $pos);
				$name = trim($name);
			}

			if(strpos($name, ':') > 0) {
				$pos = strpos($line, ':');
				$name =  substr($line, 0, $pos);
				$name = trim($name);
			}

			if( ! isset($parts[$b][$name])) {
				$parts[$b][$name] = [];
			}

			$parts[$b][$name][] = $line;
		}
	}
	return $parts;
}

function build_style_for(array $styles) : string
{
	$deps = [
		'tacbase' => ['', ''],
		'tacmin' => ['@media screen and (min-width: 30em) {', '}'],
		'tacmid' => ['@media screen and (min-width: 30em) and (max-width: 60em) {', '}'],
		'tacmax' => ['@media screen and (min-width: 60em) {', '}'],
	];

	$css = file_get_contents(CONTENT . '/css/normalize.css') . "\n";
	$css .= file_get_contents(CONTENT . '/css/pretac.css') . "\n";

	$sts = parse_styles();

	foreach($sts as $section => $s)
	{
		$css .= "{$deps[$section][0]}\n";
		foreach($s as $k => $p) {
			foreach($styles as $xc) {
				if (".{$xc}" == $k) {
					foreach($p as $n) {
						$css .= $n . PHP_EOL;
					}
				}
			}
		}
		$css .= "{$deps[$section][1]}\n";
	}

	return $css;
}

function compress_style($content) : string
{
	$command = ROOT . '/node_modules/crass/bin/crass';
	$tmpfname = tempnam("/tmp", "site");
	file_put_contents($tmpfname, $content);
	$content = exec("{$command} {$tmpfname} --optimize");
	unlink($tmpfname);
	return $content;
}

function get_styles(string $content) : array
{
	$styles = [];
	preg_match_all('/class="(.*?)"/', $content, $matches);
	foreach($matches[1] as $match) {
		$match = trim($match);
		$match = preg_replace('/\s+/', ' ', $match);
		$parts = explode(' ', $match);
		foreach($parts as $part) {
			if (! in_array($part, $styles)) {
				$styles[] = $part;
			}
		}
	}
	return $styles;
}

function style(string $filename) : void
{
	$content = file_get_contents($filename);
	$styles = get_styles($content);
	$css = build_style_for($styles);
	
	if(! DEBUG) {
		$css = compress_style($css);
	}
	
	$content = str_replace('body{color:#333;}', $css, $content);
	file_put_contents($filename, $content);
}

function styles($dir = null) : void
{
	if (is_null($dir)) {
		$dir = OUTPUT;
	}

	$files = scandir($dir);
	foreach($files as $file) {
		if(in_array($file, ['.', '..'])) {continue;}
		if (is_dir("{$dir}/{$file}")) {
			styles("{$dir}/{$file}");
		}
		if(substr($file, -4) != 'html') {continue;}
		style("{$dir}/{$file}");
	}
}