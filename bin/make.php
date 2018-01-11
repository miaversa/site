<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/vendor/autoload.php";

define('ROOT', dirname(__DIR__));
define('CONTENT', ROOT . '/content');
define('OUTPUT', ROOT . '/output');

$site = Miaversa\get_site('/site.json');

$template = Miaversa\get_renderer('/templates');
$template->addGlobal('site', $site);
$template->addGlobal('urls', new Miaversa\Statico\URL($site));

// pages
Miaversa\render_pages($template);

// products
Miaversa\render_products($template);

// collections
Miaversa\render_collections($template);

// blog
Miaversa\render_blog($template);

// index
Miaversa\index($template);
Miaversa\robots($template);
Miaversa\sitemap($template);

// images
Miaversa\copy_images();
