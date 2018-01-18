<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class Styles
{
	private $color = 'purple';
	private $light_color = 'light-purple';

	public function title()
	{
		return "{$this->color} roboto-slab";
	}

	public function color_link()
	{
		return "link {$this->color} hover-{$this->light_color} underline-hover";
	}
}
