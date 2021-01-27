<?php declare(strict_types = 1);

namespace App\ProductModule\Presenter;

class StepSixPresenter extends \App\Presenter\BasePresenter
{

	/**
	 * @var \App\ProductModule\Model\SimpleProductService
	 */
	private $productService;


	public function __construct(
		\App\ProductModule\Model\SimpleProductService $productService
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
				':Product:StepSix:default',
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
		$subQuery = new \Spameri\ElasticQuery\Query\QueryCollection();
		$subQuery->addMustQuery(
			new \Spameri\ElasticQuery\Query\MultiMatch(
				[
					'name.czechDictionary',
					'name.edgeNgram',
					'name.wordSplit',
					'name.wordJoin',
				],
				$queryString,
				3,
				\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
				\Spameri\ElasticQuery\Query\Match\Operator::OR,
				new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO),
				'czechDictionary'
			)
		);
		$query->addMustQuery($subQuery);

		$query->options()->changeSize(20);

		$filterForPrice = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filterForPrice->must()->add(
			new \Spameri\ElasticQuery\Query\Term(
				'availability',
				'skladem'
			)
		);

		$query->addAggregation(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'price',
				$filterForPrice,
				new \Spameri\ElasticQuery\Aggregation\Range(
					'price',
					TRUE,
					new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
						new \Spameri\ElasticQuery\Aggregation\RangeValue('0 - 50 Kč', 0, 50),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('50 - 100 Kč', 50, 100),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('100 - 200 Kč', 100, 200),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('200 - 500 Kč', 200, 500),
						new \Spameri\ElasticQuery\Aggregation\RangeValue('500 - 1000 Kč', 500, 1000)
					)
				)
			)
		);
		$query->addAggregation(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'category',
				NULL,
				new \Spameri\ElasticQuery\Aggregation\Term(
					'categories'
				)
			)
		);

		$query->addAggregation(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'brands',
				NULL,
				new \Spameri\ElasticQuery\Aggregation\Term(
					'brand',
					10
				)
			)
		);

		$priceFrom = $this->getParameter('priceFrom');
		$priceTo = $this->getParameter('priceTo');
		if ($priceFrom !== NULL && $priceTo !== NULL ) {
			$subQuery->addMustQuery(
				new \Spameri\ElasticQuery\Query\Range(
					'price',
					$priceFrom,
					$priceTo
				)
			);
		}

		$category = $this->getParameter('category');
		if ($category) {
			$categoryTerm = new \Spameri\ElasticQuery\Query\Term(
				'categories',
				$category
			);
			$subQuery->addMustQuery($categoryTerm);
			$filterForPrice->must()->add($categoryTerm);
		}

		$brand = $this->getParameter('brand');
		if ($brand) {
			$brandTerm = new \Spameri\ElasticQuery\Query\Term(
				'brand',
				$brand
			);
			$subQuery->addMustQuery($brandTerm);
			$filterForPrice->must()->add($brandTerm);
		}


		return $query;
	}

}
