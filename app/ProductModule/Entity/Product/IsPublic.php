<?php declare(strict_types = 1);

namespace App\ProductModule\Entity\Product;


class IsPublic implements \Spameri\Elastic\Entity\ValueInterface
{

	/**
	 * @var bool
	 */
	private $value;


	public function __construct(
		bool $value
	)
	{
		$this->value = $value;
	}


	public function value() : bool
	{
		return $this->value;
	}

}
