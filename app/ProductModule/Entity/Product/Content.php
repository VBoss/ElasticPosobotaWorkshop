<?php declare(strict_types = 1);

namespace App\ProductModule\Entity\Product;


class Content implements \Spameri\Elastic\Entity\ValueInterface
{

	/**
	 * @var string
	 */
	private $value;


	public function __construct(
		string $value
	)
	{
		if (\strlen($value) > 2048) {
			$value = \substr($value, 0, 2048);
		}

		$this->value = $value;
	}


	public function value() : string
	{
		return $this->value;
	}


	public function __toString() : string
	{
		return $this->value;
	}

}
