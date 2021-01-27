<?php declare(strict_types=1);

namespace App\WorkshopTwoModule\Model\ExportToElastic;

class PrepareImportData implements \Spameri\Elastic\Import\PrepareImportDataInterface
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


	public function prepare($entityData): \Spameri\Elastic\Entity\AbstractImport
	{
		try {
			$query = new \Spameri\ElasticQuery\ElasticQuery();
			$query->addMustQuery(
				new \Spameri\ElasticQuery\Query\Term(
					'databaseId',
					$entityData['id']
				)
			);
			$existingProduct = $this->workshopProductService->getBy($query);
			$elasticId = $existingProduct->id();

		} catch (\Spameri\Elastic\Exception\AbstractElasticSearchException $exception) {
			$elasticId = new \Spameri\Elastic\Entity\Property\EmptyElasticId();
		}

		$split = \str_split((string) $entityData['library_id']);
		$imageSrc = 'https://cdn.benu.cz/images/img-small-product/'
			. \end($split) . '/'
			. $entityData['library_id'] . '.jpg'
		;

		$tags = [];
		if ($entityData['isNew']) {
			$tags[] = 'Novinka';
		}
		if ($entityData['isFreeTransport']) {
			$tags[] = 'Doprava zdarma';
		}
		if ($entityData['isAction']) {
			$tags[] = 'Akční cena';
		}

		$purpose[] = \array_rand(\array_flip(\App\ProductModule\Model\ExportToElastic\Purposes::VALUES));
		$brand = \array_rand(\array_flip(\App\ProductModule\Model\ExportToElastic\Brands::VALUES));
		$categories[] = \array_rand(\array_flip(\App\ProductModule\Model\ExportToElastic\Categories::VALUES));
		$categories[] = \array_rand(\array_flip(\App\ProductModule\Model\ExportToElastic\Categories::VALUES));

		return new \App\WorkshopTwoModule\Entity\WorkshopProduct(
			$elasticId,
			(int) $entityData['id'],
			$entityData['name'],
			$entityData['content_description'],
			$entityData['alias'],
			$imageSrc,
			(float) $entityData['amount'],
			$entityData['availability_id'] === '1' ? 'Skladem' : 'Nedostupné',
			$tags,
			$categories,
			$purpose,
			\random_int(0, 1000),
			$brand
		);
	}

}
