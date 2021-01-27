<?php declare(strict_types=1);

namespace App\ProductModule\Model;

class WorkshopProductService extends \Spameri\Elastic\Model\AbstractBaseService
{

	/**
	 * @param \Spameri\ElasticQuery\ElasticQuery $elasticQuery
	 * @return \Spameri\Elastic\Entity\ElasticEntityInterface&\App\ProductModule\Entity\WorkshopProduct
	 */
	public function getBy(\Spameri\ElasticQuery\ElasticQuery $elasticQuery): \Spameri\Elastic\Entity\ElasticEntityInterface
	{
		return parent::getBy($elasticQuery);
	}

}
