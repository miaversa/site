<?php
declare(strict_types=1);

namespace Miaversa;

final class URL
{
	private $site;

	public function __construct(Site $site)
	{
		$this->site = $site;
	}
}