<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class Page
{
	public $slug;
	public $name;
	public $description;
	public $content;

	public function __construct(string $slug, string $name, string $desc, string $content)
	{
		$this->slug = $slug;
		$this->name = $name;
		$this->description = $desc;
		$this->content = $content;
	}

	public function __set($name, $value)
	{
		throw new \Exception("propertie {$name} does not exists.");
	}
}