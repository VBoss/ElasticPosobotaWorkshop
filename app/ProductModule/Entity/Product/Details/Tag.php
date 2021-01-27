<?php declare(strict_types = 1);

namespace App\ProductModule\Entity\Product\Details;


class Tag implements \Spameri\Elastic\Entity\ValueInterface
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
			throw new \InvalidArgumentException('Empty string is not supported for product tag: ' . $value);
		}
		if (\strlen($value) > 55) {
			$value = \substr($value, 0, 55);
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
