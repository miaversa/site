<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/vendor/autoload.php";

define('ROOT', dirname(__DIR__));
define('CONTENT', ROOT . '/content');
define('OUTPUT', ROOT . '/output');

if(false === getenv('SALT')) {
	print "salt missing\n";
	exit();
}

function main() {
	$site = Miaversa\get_site('/site.json');
	$template = Miaversa\get_renderer('/templates');
	$template->addGlobal('site', $site);
	$template->addGlobal('urls', new Miaversa\Statico\URL($site));

	Miaversa\render_pages($template);
	Miaversa\render_products($template);
	Miaversa\render_collections($template);
	Miaversa\render_blog($template);
	Miaversa\index($template);
	Miaversa\robots($template);
	Miaversa\sitemap($template);
	Miaversa\copy_images();
}

main();
