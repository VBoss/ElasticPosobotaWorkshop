<?php declare(strict_types = 1);

namespace App\ProductModule\ElasticQuery;

class SynonymAnalyzer
	implements \Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface,
	\Spameri\ElasticQuery\Collection\Item
{

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	 */
	protected $filter;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Filter\Stop
	 */
	protected $stopFilter;

	/**
	 * @var array
	 */
	private $synonym;


	public function __construct(
		?\Spameri\ElasticQuery\Mapping\Filter\Stop $stopFilter = NULL,
		array $synonym
	)
	{
		$this->stopFilter = $stopFilter;
		$this->synonym = $synonym;
	}


	public function name(): string
	{
		return 'czechSynonym';
	}


	public function filter(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	{
		if ( ! $this->filter instanceof \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection) {
			$this->filter = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase()
			);
			if ($this->stopFilter) {
				$this->filter->add($this->stopFilter);

			} else {
				$this->filter->add(
					new \Spameri\ElasticQuery\Mapping\Filter\Stop\Czech()
				);
			}
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Synonym(
					$this->synonym
				)
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase()
			);
			if ($this->stopFilter) {
				$this->filter->add($this->stopFilter);

			} else {
				$this->filter->add(
					new \Spameri\ElasticQuery\Mapping\Filter\Stop\Czech()
				);
			}
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Unique()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\ASCIIFolding()
			);
		}

		return $this->filter;
	}

	public function key() : string
	{
		return $this->name();
	}


	public function getType() : string
	{
		return 'custom';
	}


	public function tokenizer() : string
	{
		return 'standard';
	}


	public function toArray() : array
	{
		$filterArray = [];
		/** @var \Spameri\ElasticQuery\Mapping\FilterInterface $filter */
		foreach ($this->filter() as $filter) {
			if ($filter instanceof \Spameri\ElasticQuery\Mapping\Filter\Synonym) {
				$filterArray[] = $filter->getName();

			} elseif ($filter instanceof \Spameri\ElasticQuery\Mapping\Filter\Stop) {
				$filterArray[] = $filter->getName();

			} else {
				$filterArray[] = $filter->getType();
			}
		}

		return [
			$this->name() => [
				'type'      => $this->getType(),
				'tokenizer' => $this->tokenizer(),
				'filter'    => $filterArray,
			],
		];
	}


}
