<?php
declare(strict_types=1);

namespace Miaversa;

function robots(\Twig_Environment $template) : void {
	$content = $template->render('robots.txt.twig');
	put_file('/robots.txt', $content);
}
