<?php declare(strict_types=1);

namespace App\WorkshopTwoModule\Model;

class WorkshopProductService extends \Spameri\Elastic\Model\AbstractBaseService
{

	/**
	 * @return \Spameri\Elastic\Entity\ElasticEntityInterface&\App\WorkshopTwoModule\Entity\WorkshopProduct
	 */
	public function getBy(\Spameri\ElasticQuery\ElasticQuery $elasticQuery): \Spameri\Elastic\Entity\ElasticEntityInterface
	{
		return parent::getBy($elasticQuery);
	}

}
