<?php declare(strict_types = 1);

namespace App\ProductModule\Factory;


class SimpleProductCollectionFactory implements \Spameri\Elastic\Factory\CollectionFactoryInterface
{

	public function create(
		\Spameri\Elastic\Model\ServiceInterface $service
		, array $elasticIds = []
		, \Spameri\Elastic\Entity\ElasticEntityInterface ... $entityCollection
	) : \Spameri\Elastic\Entity\ElasticEntityCollectionInterface
	{
		return new \App\ProductModule\Entity\ProductCollection($service, $elasticIds, ... $entityCollection);
	}

}
