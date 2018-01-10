<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class Collection
{
	public $slug;
	public $name;
	public $description;

	public function __construct(string $slug, string $name, string $desc)
	{
		$this->slug = $slug;
		$this->name = $name;
		$this->description = $desc;
	}

	public function __set($name, $value)
	{
		throw new \Exception("propertie {$name} does not exists.");
	}
}