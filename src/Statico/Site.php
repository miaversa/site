<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class Site
{
	public $name;
	public $baseURL;
	public $cartURL;
	public $description;
	public $twitter;
	public $analytics;

	public function __construct(string $name, string $base, string $cart, string $desc, string $twitter, string $analytics)
	{
		$this->name = $name;
		$this->baseURL = $base;
		$this->cartURL = $cart;
		$this->description = $desc;
		$this->twitter = $twitter;
		$this->analytics = $analytics;
	}

	public function __set($name, $value)
	{
		throw new \Exception("propertie {$name} does not exists.");
	}
}