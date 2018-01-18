<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class Post
{
	public $slug;
	public $title;
	public $description;
	public $dateTime;
	public $summary;
	public $content;

	public function __construct(string $slug, string $title, string $desc, \DateTime $datetime, string $summary, string $content)
	{
		$this->slug = $slug;
		$this->title = $title;
		$this->description = $desc;
		$this->dateTime = $datetime;
		$this->summary = $summary;
		$this->content = $content;
	}

	public function __set($name, $value)
	{
		throw new \Exception("propertie {$name} does not exists.");
	}

	public function formatedDate()
	{
		return strftime('%A, %e de %B de %G', $this->dateTime->getTimestamp());
	}
}