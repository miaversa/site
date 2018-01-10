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
Miaversa\render_page($template, 'sobre.html');
Miaversa\render_page($template, 'perguntas-frequentes.html');

// products
Miaversa\render_products($template);

// collections
Miaversa\render_collections($template);

// index
$content = $template->render('index.html.twig');
Miaversa\put_file('/index.html', $content);

// images
Miaversa\copy_images();
