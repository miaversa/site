<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/vendor/autoload.php";

define('ROOT', dirname(__DIR__));
define('CONTENT', ROOT . '/content');
define('OUTPUT', ROOT . '/output');

$site = Miaversa\get_site('/site.json');

$template = Miaversa\get_renderer('/templates');
$template->addGlobal('site', $site);
$template->addGlobal('urls', new Miaversa\URL($site));


// pages
Miaversa\render_page($template, '/pages/sobre.html', '/sobre.html');
Miaversa\render_page($template, '/pages/perguntas-frequentes.html', '/perguntas-frequentes.html');

$content = $template->render('index.html.twig');
file_put_contents(OUTPUT . '/index.html', $content);
