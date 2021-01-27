<?php declare(strict_types = 1);

namespace App\ProductModule\Presenter;

class StepOnePresenter extends \App\Presenter\BasePresenter
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
				':Product:StepOne:default',
				[
					'queryString' => $form->getValues()->queryString,
				]
			);
		};

		return $form;
	}


	public function buildQuery(?string $queryString): \Spameri\ElasticQuery\ElasticQuery
	{
		return new \Spameri\ElasticQuery\ElasticQuery();
	}

}
