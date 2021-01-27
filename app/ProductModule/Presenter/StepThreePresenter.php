<?php declare(strict_types = 1);

namespace App\ProductModule\Presenter;

class StepThreePresenter extends \App\Presenter\BasePresenter
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
				':Product:StepThree:default',
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
		$should = new \Spameri\ElasticQuery\Query\ShouldCollection();

		$should->add(
			new \Spameri\ElasticQuery\Query\Match(
				'name',
				$queryString,
				1,
				\Spameri\ElasticQuery\Query\Match\Operator::OR,
				new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO)
			)
		);
		$should->add(
			new \Spameri\ElasticQuery\Query\WildCard(
				'name',
				'*' . $queryString . '*'
			)
		);
		$should->add(
			new \Spameri\ElasticQuery\Query\MatchPhrase(
				'name',
				$queryString
			)
		);
		$should->add(
			new \Spameri\ElasticQuery\Query\Term(
				'content',
				$queryString
			)
		);

		// ==========

		$must = new \Spameri\ElasticQuery\Query\MustCollection();
		$must->add(
			new \Spameri\ElasticQuery\Query\Term(
				'availability',
				'skladem'
			)
		);
		$must->add(
			new \Spameri\ElasticQuery\Query\Range(
				'price',
				1,
				10000000
			)
		);
		$must->add(
			new \Spameri\ElasticQuery\Query\Exists(
				'image'
			)
		);

		// ==========

		$mustNot = new \Spameri\ElasticQuery\Query\MustNotCollection();
		$mustNot->add(
			new \Spameri\ElasticQuery\Query\Term(
				'price',
				0
			)
		);

		$query->addShouldQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				$must,
				$should,
				$mustNot
			)
		);

		$query->options()->changeSize(20);

		return $query;
	}

}
