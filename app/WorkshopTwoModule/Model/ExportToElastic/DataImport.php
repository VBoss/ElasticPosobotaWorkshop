<?php declare(strict_types=1);

namespace App\WorkshopTwoModule\Model\ExportToElastic;

class DataImport implements \Spameri\Elastic\Import\DataImportInterface
{

	/**
	 * @var \App\WorkshopTwoModule\Model\WorkshopProductService
	 */
	private $workshopProductService;


	public function __construct(
		\App\WorkshopTwoModule\Model\WorkshopProductService $workshopProductService
	)
	{
		$this->workshopProductService = $workshopProductService;
	}


	/**
	 * @param \App\WorkshopTwoModule\Entity\WorkshopProduct $entity
	 */
	public function import(
		\Spameri\Elastic\Entity\AbstractImport $entity
	): \Spameri\Elastic\Import\ResponseInterface
	{
		$id = $this->workshopProductService->insert($entity);

		return new \Spameri\Elastic\Import\Response\SimpleResponse(
			$id,
			$entity
		);
	}

}
