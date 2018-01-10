<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class Product
{
	public $sku;
	public $name;
	public $slug;
	public $type;
	public $collection;
	public $date;
	public $price;
	public $description;
	public $pop;
	public $content;

	public function sign()
	{
		$salt = \Miaversa\get_salt();
		$price = number_format($this->price, 2, ',', '.');
		$sign = "#{$salt}#{$this->sku}#{$this->name}#{$price}#";
		return sha1($sign);
	}

	public function __set($name, $value)
	{
		throw new \Exception("propertie {$name} does not exists.");
	}
}
