<?php declare(strict_types = 1);

namespace App\ProductModule\Command;

class ExportToElastic extends \Symfony\Component\Console\Command\Command
{

	/**
	 * @var \App\ProductModule\Model\ExportToElastic
	 */
	private $exportToElastic;

	/**
	 * @var \Spameri\Elastic\Model\Indices\Delete
	 */
	private $delete;

	/**
	 * @var \App\ProductModule\Entity\WorkshopProductConfig
	 */
	private $workshopProductConfig;

	/**
	 * @var \Spameri\Elastic\Model\Indices\Create
	 */
	private $create;


	public function __construct(
		\App\ProductModule\Model\ExportToElastic $exportToElastic
		, \Spameri\Elastic\Model\Indices\Delete $delete
		, \Spameri\Elastic\Model\Indices\Create $create
		, \App\ProductModule\Entity\WorkshopProductConfig $workshopProductConfig
	)
	{
		parent::__construct(NULL);
		$this->exportToElastic = $exportToElastic;
		$this->delete = $delete;
		$this->create = $create;
		$this->workshopProductConfig = $workshopProductConfig;
	}


	public function configure(): void
	{
		$this->setName('exportToElastic');
	}


	public function execute(
		\Symfony\Component\Console\Input\InputInterface $input,
		\Symfony\Component\Console\Output\OutputInterface $output
	)
	{
		$options = new \Spameri\Elastic\Import\Run\Options(600);

		// Clear index
		try {
			$this->delete->execute($this->workshopProductConfig->provide()->indexName());

		} catch (\Spameri\Elastic\Exception\ElasticSearchException $exception) {}

		// Create index
		$this->create->execute(
			$this->workshopProductConfig->provide()->indexName(),
			$this->workshopProductConfig->provide()->toArray()
		);

		// Export
		$this->exportToElastic->execute($options);
	}

}
