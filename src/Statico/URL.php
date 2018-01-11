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

	public function sitemapURL() : string
	{
		$url = "{$this->site->baseURL}/sitemap.xml";
		return $url;
	}

	public function relativePostURL(Post $post) : string
	{
		$datepart = $post->dateTime->format("Y/m");
		$url = "/blog/{$datepart}/{$post->slug}/";
		return $url;
	}

	public function productLD(Product $product) : string
	{
		$schema = [
			"@context" => "http://schema.org",
			"@type"    => "Product",
			"sku"      => $product->sku,
			"name"     => $product->name,
			"image"    => [
				$this->productMediaURL('1x1', $product),
				$this->productMediaURL('4x3', $product),
				$this->productMediaURL('16x9', $product)
			],
			"description" => $product->description,
			"brand" => [
				"@type" => "Thing",
				"name"  => $this->site->name
			],
			"offers" => [
				"@type"           => "Offer",
				"priceCurrency"   => "BRL",
				"price"           => $product->price,
				"priceValidUntil" => "2020-01-01",
				"itemCondition"   => "http://schema.org/NewCondition",
				"availability"    => "http://schema.org/InStock"
			]
		];

		$ld = json_encode($schema, JSON_UNESCAPED_SLASHES);
		return $ld;
	}
}