<?php declare(strict_types = 1);

namespace App\ProductModule\Entity\Product;


class ParameterValue implements \Spameri\Elastic\Entity\EntityInterface
{

	/**
	 * @var string
	 */
	private $parameterName;

	/**
	 * @var string
	 */
	private $parameterValueUid;

	/**
	 * @var string
	 */
	private $stringParameterValue;

	/**
	 * @var bool
	 */
	private $boolParameterValue;

	/**
	 * @var int
	 */
	private $intParameterValue;


	public function __construct(
		string $parameterName
		, string $parameterValueUid
		, string $stringParameterValue
		, bool $boolParameterValue
		, int $intParameterValue
	)
	{
		$this->parameterName = $parameterName;
		$this->parameterValueUid = $parameterValueUid;
		$this->stringParameterValue = $stringParameterValue;
		$this->boolParameterValue = $boolParameterValue;
		$this->intParameterValue = $intParameterValue;
	}


	public function key() : string
	{
		return $this->parameterValueUid;
	}


	public function entityVariables() : array
	{
		return \get_object_vars($this);
	}


	public function parameterName() : string
	{
		return $this->parameterName;
	}


	public function parameterValueUid() : string
	{
		return $this->parameterValueUid;
	}


	public function stringParameterValue() : string
	{
		return $this->stringParameterValue;
	}


	public function boolParameterValue() : bool
	{
		return $this->boolParameterValue;
	}


	public function intParameterValue() : int
	{
		return $this->intParameterValue;
	}

}
