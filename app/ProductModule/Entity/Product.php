<?php declare(strict_types = 1);

namespace App\ProductModule\Entity;


class Product implements \Spameri\Elastic\Entity\ElasticEntityInterface
{

	/**
	 * @var \Spameri\Elastic\Entity\Property\ElasticIdInterface
	 */
	private $id;

	/**
	 * @var \App\ProductModule\Entity\Product\IsPublic
	 */
	private $isPublic;

	/**
	 * @var \App\ProductModule\Entity\Product\Name
	 */
	private $name;

	/**
	 * @var \App\ProductModule\Entity\Product\Content
	 */
	private $content;

	/**
	 * @var \App\ProductModule\Entity\Product\Details
	 */
	private $details;

	/**
	 * @var \App\ProductModule\Entity\Product\Price
	 */
	private $price;

	/**
	 * @var \App\ProductModule\Entity\Product\ParameterValuesCollection
	 */
	private $parameterValues;


	public function __construct(
		\Spameri\Elastic\Entity\Property\ElasticIdInterface $id
		, \App\ProductModule\Entity\Product\IsPublic $isPublic
		, \App\ProductModule\Entity\Product\Name $name
		, \App\ProductModule\Entity\Product\Content $content
		, \App\ProductModule\Entity\Product\Details $details
		, \App\ProductModule\Entity\Product\Price $price
		, \App\ProductModule\Entity\Product\ParameterValuesCollection $parameterValues
	)
	{
		$this->id = $id;
		$this->isPublic = $isPublic;
		$this->name = $name;
		$this->content = $content;
		$this->details = $details;
		$this->price = $price;
		$this->parameterValues = $parameterValues;
	}


	public function id(): \Spameri\Elastic\Entity\Property\ElasticIdInterface
	{
		return $this->id;
	}


	public function entityVariables(): array
	{
		return \get_object_vars($this);
	}


	public function isPublic() : \App\ProductModule\Entity\Product\IsPublic
	{
		return $this->isPublic;
	}


	public function name() : \App\ProductModule\Entity\Product\Name
	{
		return $this->name;
	}


	public function content() : \App\ProductModule\Entity\Product\Content
	{
		return $this->content;
	}


	public function details() : \App\ProductModule\Entity\Product\Details
	{
		return $this->details;
	}


	public function price() : \App\ProductModule\Entity\Product\Price
	{
		return $this->price;
	}


	public function parameterValues() : \App\ProductModule\Entity\Product\ParameterValuesCollection
	{
		return $this->parameterValues;
	}

}
