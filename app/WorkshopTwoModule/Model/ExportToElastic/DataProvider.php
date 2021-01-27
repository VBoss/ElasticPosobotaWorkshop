<?php declare(strict_types=1);

namespace App\WorkshopTwoModule\Model\ExportToElastic;

class DataProvider implements \Spameri\Elastic\Import\DataProviderInterface
{

	public function provide(\Spameri\Elastic\Import\Run\Options $options): \Generator
	{
		$csvFile = \fopen(__DIR__ . '/../../../../sql.csv', 'rb');
		// $data = \file_get_contents();
		// \Atrox\Matcher::matchSingle('XPath');
		// Jako alternativa pro zdroj dat, když chci crawler.

		while ($data = \fgetcsv($csvFile, 0, ',')) {
			yield [
				'id' => $data[0],
				'name' => $data[1],
				'content_description' => $data[2],
				'alias' => $data[3],
				'library_id' => $data[4],
				'amount' => $data[5],
				'availability_id' => $data[6],
				'isNew' => $data[7],
				'isFreeTransport' => $data[8],
				'isAction' => $data[9],
			];
		}
	}


	public function count(\Spameri\Elastic\Import\Run\Options $options): int
	{
		return 23700;
	}

}
