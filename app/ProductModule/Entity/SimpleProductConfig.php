<?php declare(strict_types = 1);

namespace App\ProductModule\Entity;

class SimpleProductConfig implements \Spameri\Elastic\Settings\IndexConfigInterface
{

	/**
	 * @var string
	 */
	private $indexName;


	public function __construct(
		string $indexName
	)
	{
		$this->indexName = $indexName;
	}


	public function provide(): \Spameri\ElasticQuery\Mapping\Settings
	{
		// ===== ANALYZERS =====

		$stopWords = [
			'angin',
			'ml',
			'l',
			'litr',
			'litry',
			'litrů',
			'ks',
			'pastilka',
			'pastilky',
			'pastilek',
			'kapsle',
			'kapslí',
			'tbl',
			'tob',
			'dr',
			'cps',
			'x',
			'g',
			'mg',
			'báze',
			'bázi',
			'e',
			'dp',
			'duo',
			'v',
			'a',
			'plus',
			'por',
			'nob',
			'zn',
			'la',
			'lr',
			'b6',
			'n',
		];
		$settings = new \Spameri\ElasticQuery\Mapping\Settings($this->indexName);
		$stopFilter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\Czech($stopWords);
		$czechDictionary = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\CzechDictionary($stopFilter);
		$settings->addAnalyzer($czechDictionary);

		$lowerCase = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\Lowercase();
		$settings->addAnalyzer($lowerCase);

		$edgeNgram = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EdgeNgram(
			2,
			6,
			$stopFilter
		);
		$settings->addAnalyzer($edgeNgram);

		$worldSplit = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\WordDelimiter(
			$stopFilter
		);
		$settings->addAnalyzer($worldSplit);

		$worldJoin = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\CommonGrams(
			$stopWords
		);
		$settings->addAnalyzer($worldJoin);

		// ===== FIELDS =====

		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'databaseId',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_KEYWORD
			)
		);

		$nameFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'name',
			\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT
		);
		$nameFields->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'czechDictionary',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$czechDictionary
			)
		);
		$nameFields->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'edgeNgram',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$edgeNgram
			)
		);
		$nameFields->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'wordSplit',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$worldSplit
			)
		);
		$nameFields->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'wordJoin',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$worldJoin
			)
		);
		$settings->addMappingSubField($nameFields);

		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'content',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$czechDictionary
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'alias',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_KEYWORD
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'image',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_KEYWORD
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'price',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_DOUBLE
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'availability',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$lowerCase
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'tags',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$lowerCase,
				TRUE
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'categories',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$lowerCase,
				TRUE
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'purpose',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				$lowerCase,
				TRUE
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'venality',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_INTEGER
			)
		);
		$settings->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'brand',
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_TEXT,
				NULL,
				TRUE
			)
		);

		return $settings;
	}

}
