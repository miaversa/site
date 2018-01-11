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

	public function relativePageURL(Page $page) : string
	{
		return "{$page->slug}/";
	}

	public function pageURL(Page $page) : string
	{
		return $this->siteURL() . $this->relativePageURL($page);
	}

	public function cartURL() : string
	{
		return "{$this->site->cartURL}/";
	}

	public function relativeProductURL(Product $product) : string
	{
		$url = "/produtos/{$product->collection}/{$product->slug}/";
		return $url;
	}

	public function productURL(Product $product) : string
	{
		return $this->site->baseURL . $this->relativeProductURL($product);
	}

	public function productMediaURL(string $site, Product $product) : string
	{
		$url = "{$this->site->baseURL}/images/{$site}/{$product->slug}.jpg";
		return $url;
	}

	public function relativeCollectionURL(Collection $collection, Type $type = null, $order = null) : string
	{
		if(is_array($order)) {
			$order = $order['slug'];
		}

		$url = "/produtos/{$collection->slug}";

		if( ! is_null($type)) {
			$url .= "/{$type->slug}";
		}

		if( ! is_null($order)) {
			$url .= "/{$order}";
		}

		$url .= '/';

		return $url;
	}

	public function sitemapURL()
	{
		$url = "{$this->site->baseURL}/sitemap.xml";
		return $url;
	}
}