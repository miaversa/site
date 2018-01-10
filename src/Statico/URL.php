<?php
declare(strict_types=1);

namespace Miaversa\Statico;

final class URL
{
	private $site;

	public function __construct(Site $site)
	{
		$this->site = $site;
	}

	public function siteURL() : string
	{
		return "{$this->site->baseURL}/";
	}

	public function relativeProductURL(Product $product) : string
	{
		$url = "/produtos/{$product->collection}/{$product->slug}/";
		return $url;
	}

	public function productURL(Product $product) : string
	{
		return $this->site->baseURL . $this->relativeProductURL($product);;
	}

	public function productMediaURL(string $site, Product $product) : string
	{
		$url = "{$this->site->baseURL}/images/{$site}/{$product->slug}.jpg";
		return $url;
	}
}