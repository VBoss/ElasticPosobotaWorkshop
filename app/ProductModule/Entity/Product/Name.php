<?php declare(strict_types = 1);

namespace App\ProductModule\Entity\Product;


class Name implements \Spameri\Elastic\Entity\ValueInterface
{

	/**
	 * @var string
	 */
	private $value;


	public function __construct(
		string $value
	)
	{
		if (\strlen($value) < 0) {
			throw new \InvalidArgumentException('Empty string is not supported for product name: ' . $value);
		}
		if (\strlen($value) > 255) {
			$value = \substr($value, 0, 255);
		}

		$this->value = $value;
	}


	public function value() : string
	{
		return $this->value;
	}


}
