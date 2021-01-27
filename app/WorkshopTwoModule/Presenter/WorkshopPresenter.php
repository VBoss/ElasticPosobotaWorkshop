<?php declare(strict_types = 1);

namespace App\WorkshopTwoModule\Presenter;

class WorkshopPresenter extends \App\Presenter\BasePresenter
{

	/**
	 * @var \App\WorkshopTwoModule\Model\WorkshopProductService
	 */
	private $productService;


	public function __construct(
		\App\WorkshopTwoModule\Model\WorkshopProductService $productService
	)
	{
		parent::__construct();
		$this->productService = $productService;
	}


	public function renderDefault($queryString): void
	{
		$query = $this->buildQuery($queryString);

		try {
			$products = $this->productService->getAllBy($query);

		} catch (\Spameri\Elastic\Exception\AbstractElasticSearchException $exception) {
			$products = [];
			\Tracy\Debugger::barDump($exception);
		}

		$this->getTemplate()->add(
			'products',
			$products
		);
		$this->getTemplate()->add(
			'queryString',
			$queryString
		);
	}


	public function createComponentSearchForm() :\Nette\Application\UI\Form
	{
		$form = new \Nette\Application\UI\Form();
		$form->addText('queryString', 'query')
			->setAttribute('class', 'inp-text suggest')
		;

		$form->addSubmit('search', 'Search');

		$form->onSuccess[] = function () use ($form) {
			$this->redirect(
				301,
				':WorkshopTwo:Workshop:default',
				[
					'queryString' => $form->getValues()->queryString,
				]
			);
		};

		return $form;
	}


	public function buildQuery(?string $queryString): \Spameri\ElasticQuery\ElasticQuery
	{
		$query = new \Spameri\ElasticQuery\ElasticQuery();
		$shouldCollection = new \Spameri\ElasticQuery\Query\ShouldCollection();
		$mustCollection = new \Spameri\ElasticQuery\Query\MustCollection();
		$mustNotCollection = new \Spameri\ElasticQuery\Query\MustNotCollection();

		$mustCollection->add(
			new \Spameri\ElasticQuery\Query\MultiMatch(
				[
					'name.czechDictionary',
					'name.edgeNgram',
					'name.wordSplit',
					'name.wordJoin',
					'name.czechSynonym',
				],
				$queryString,
				3,
				\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
				\Spameri\ElasticQuery\Query\Match\Operator::OR,
				new \Spameri\ElasticQuery\Query\Match\Fuzziness(
					\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO
				),
				'czechDictionary'
			)
		);

		// ===============

		$mustCollection->add(
			new \Spameri\ElasticQuery\Query\Term(
				'availability',
				'skladem'
			)
		);

		$mustCollection->add(
			new \Spameri\ElasticQuery\Query\Range(
				'price',
				1
			)
		);

		$mustCollection->add(
			new \Spameri\ElasticQuery\Query\Exists('image')
		);

		$query->addMustQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				$mustCollection,
				$shouldCollection,
				$mustNotCollection
			)
		);

		$query->options()->changeSize(20);


		// =========== AGGS


		$query->addAggregation(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'price',
				NULL,
				new \Spameri\ElasticQuery\Aggregation\Range(
					'price',
					TRUE,
					new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
						new \Spameri\ElasticQuery\Aggregation\RangeValue(
							'0 - 50 Kč',
							0,
							50
						),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('50 - 100 Kč', 50, 100),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('100 - 200 Kč', 100, 200),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('200 - 500 Kč', 200, 500),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('500 - 1000 Kč', 500, 1000)
					)
				)
			)
		);


		$priceFrom = $this->getParameter('priceFrom');
		$this->template->add('priceFrom', $priceFrom);
		$priceTo = $this->getParameter('priceTo');
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		if ($priceFrom !== NULL && $priceTo !== NULL ) {
			$filter->must()->add(
				new \Spameri\ElasticQuery\Query\Range(
					'price',
					$priceFrom,
					$priceTo
				)
			);
		}
		$query->addAggregation(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'categories',
				$filter,
				new \Spameri\ElasticQuery\Aggregation\Term(
					'categories',
					100
				)
			)
		);

		$query->addAggregation(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'brands',
				NULL,
				new \Spameri\ElasticQuery\Aggregation\Term(
					'brand',
					1000
				)
			)
		);

		return $query;
	}

}
