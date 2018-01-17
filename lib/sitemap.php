<?php
declare(strict_types=1);

namespace Miaversa;

function sitemap(\Twig_Environment $template) : void {
	$variables = [
		'products' => products(),
		'pages' => pages(),
	];
	
	$content = $template->render('sitemap.xml.twig', $variables);
	put_file('/sitemap.xml', $content);
}
