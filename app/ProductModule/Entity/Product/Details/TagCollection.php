<?php declare(strict_types = 1);

namespace App\ProductModule\Entity\Product\Details;


class TagCollection implements \Spameri\Elastic\Entity\ValueCollectionInterface
{

	/**
	 * @var \App\ProductModule\Entity\Product\Details\Tag[]
	 */
	private $collection;


	public function __construct(
		\App\ProductModule\Entity\Product\Details\Tag ... $collection
	)
	{
		$this->collection = [];
		foreach ($collection as $tag) {
			$this->add($tag);
		}
	}


	public function add(
		\App\ProductModule\Entity\Product\Details\Tag $tag
	) : void
	{
		$this->collection[$tag->value()] = $tag;
	}


	public function find(
		\App\ProductModule\Entity\Product\Details\Tag $tag
	) : ?\App\ProductModule\Entity\Product\Details\Tag
	{
		foreach ($this->collection as $value) {
			if ($tag->value() === $value->value()) {
				return $value;
			}
		}

		return NULL;
	}


	public function keys() : array
	{
		return \array_keys($this->collection);
	}


	public function first() : ?\App\ProductModule\Entity\Product\Details\Tag
	{
		return \reset($this->collection);
	}


	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->collection);
	}

}
