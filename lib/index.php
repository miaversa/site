<?php
declare(strict_types=1);

namespace Miaversa;

function index(\Twig_Environment $template)
{
	$content = $template->render('index.html.twig');
	put_file('/index.html', $content);
}