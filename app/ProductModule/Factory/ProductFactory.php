<?php declare(strict_types = 1);

namespace App\ProductModule\Factory;


class ProductFactory implements \Spameri\Elastic\Factory\EntityFactoryInterface
{

	/**
	 * @var \Spameri\Elastic\Model\ServiceLocator
	 */
	private $serviceLocator;


	public function __construct(
		\Spameri\Elastic\Model\ServiceLocator $serviceLocator
	)
	{
		$this->serviceLocator = $serviceLocator;
	}


	public function create(\Spameri\ElasticQuery\Response\Result\Hit $hit) : \Generator
	{
		$productService = $this->serviceLocator->locateByEntityClass(\App\ProductModule\Entity\Product::class);

		yield new \App\ProductModule\Entity\Product(
			new \Spameri\Elastic\Entity\Property\ElasticId($hit->id()),
			new \App\ProductModule\Entity\Product\IsPublic($hit->source()['isPublic']),
			new \App\ProductModule\Entity\Product\Name($hit->source()['name']),
			new \App\ProductModule\Entity\Product\Content($hit->source()['content']),
			new \App\ProductModule\Entity\Product\Details(
				new \App\ProductModule\Entity\Product\Details\TagCollection( ... $this->createTags($hit->source()['details']['tags'])),
				new \App\ProductModule\Entity\Product\Details\Accessories(
					$productService,
					$hit->source()['details']['accessories'] ?? NULL
				)
			),
			new \App\ProductModule\Entity\Product\Price($hit->source()['price']),
			new \App\ProductModule\Entity\Product\ParameterValuesCollection()
		);
	}


	protected function createTags(
		array $tags
	): \Generator
	{
		foreach ($tags as $tag) {
			yield new \App\ProductModule\Entity\Product\Details\Tag($tag);
		}
	}

}
