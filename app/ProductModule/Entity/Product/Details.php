<?php declare(strict_types = 1);

namespace App\ProductModule\Entity\Product;


class Details implements \Spameri\Elastic\Entity\EntityInterface
{

	/**
	 * @var \App\ProductModule\Entity\Product\Details\TagCollection
	 */
	private $tags;

	/**
	 * @var \App\ProductModule\Entity\Product\Details\Accessories
	 */
	private $accessories;


	public function __construct(
		\App\ProductModule\Entity\Product\Details\TagCollection $tags,
		\App\ProductModule\Entity\Product\Details\Accessories $accessories
	)
	{
		$this->tags = $tags;
		$this->accessories = $accessories;
	}


	public function key(): string
	{
		return 'Only if this is somewhere part of collection.';
	}


	public function entityVariables(): array
	{
		return \get_object_vars($this);
	}


	public function tags(): \App\ProductModule\Entity\Product\Details\TagCollection
	{
		return $this->tags;
	}


	public function accessories(): \App\ProductModule\Entity\Product\Details\Accessories
	{
		return $this->accessories;
	}

}
