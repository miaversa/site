<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class Site
{
	public $name;
	public $baseURL;
	public $description;
	public $twitter;

	public function __construct(string $name, string $base, string $desc, string $twitter)
	{
		$this->name = $name;
		$this->baseURL = $base;
		$this->description = $desc;
		$this->twitter = $twitter;
	}

	public function __set($name, $value)
	{
		throw new \Exception("propertie {$name} does not exists.");
	}
}