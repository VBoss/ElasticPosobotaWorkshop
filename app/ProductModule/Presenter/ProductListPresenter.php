<?php declare(strict_types = 1);

namespace App\ProductModule\Presenter;


class ProductListPresenter extends \App\Presenter\BasePresenter
{

	/**
	 * @var \App\ProductModule\Model\ProductService
	 */
	private $productService;

	/**
	 * @var \Spameri\Elastic\Model\Search
	 */
	private $search;


	public function __construct(
		\App\ProductModule\Model\ProductService $productService
		, \Spameri\Elastic\Model\Search $search
	)
	{
		$this->productService = $productService;
		$this->search = $search;
	}


	public function renderDefault(): void
	{

		$this->getTemplate()->add(
			'products',
			$this->productService->getAllBy(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						new \Spameri\ElasticQuery\Query\MustCollection(
							new \Spameri\ElasticQuery\Query\Term(
								'isPublic',
								TRUE
							),
							new \Spameri\ElasticQuery\Query\QueryCollection(
								NULL,
								new \Spameri\ElasticQuery\Query\ShouldCollection(
									new \Spameri\ElasticQuery\Query\Match(
										'name',
										'drzak na mobl',
										10.0,
										\Spameri\ElasticQuery\Query\Match\Operator::OR,
										new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO'),
										NULL,
										NULL
									),
									new \Spameri\ElasticQuery\Query\Match(
										'content',
										'drzak na mobl'
									)
								)
							)
						)
					)
				)
			)
		);

		try {
			$holder = $this->productService->getAllBy(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						new \Spameri\ElasticQuery\Query\MustCollection(
							new \Spameri\ElasticQuery\Query\Match(
								'name',
								'drzak',
								2,
								\Spameri\ElasticQuery\Query\Match\Operator::OR,
								new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO')
							)
						)
					)
				)
			);

		} catch (\Spameri\Elastic\Exception\DocumentNotFound $exception) {

		}

		$this->getTemplate()->add(
			'search',
			$this->search->execute(
				new \Spameri\ElasticQuery\ElasticQuery(
					NULL,
					NULL,
					NULL,
					new \Spameri\ElasticQuery\Aggregation\AggregationCollection(
						NULL,
						new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
							'priceHistogram',
							NULL,
							new \Spameri\ElasticQuery\Aggregation\Histogram(
								'price',
								1000
							),
							new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
								'priceRange',
								NULL,
								new \Spameri\ElasticQuery\Aggregation\Range(
									'price',
									TRUE,
									new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
										new \Spameri\ElasticQuery\Aggregation\RangeValue('100-200', 100, 200),
										new \Spameri\ElasticQuery\Aggregation\RangeValue('200-1000', 200, 1000)
									)
								)
							)
						)
					)
				),
				'spameri_product'
			)
		);

	}

}
