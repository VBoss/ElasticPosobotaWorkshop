<?php declare(strict_types=1);

namespace App\ProductModule\Entity;

class WorkshopProduct extends \Spameri\Elastic\Entity\AbstractImport implements \Spameri\Elastic\Entity\ElasticEntityInterface
{

	/**
	 * @var \Spameri\Elastic\Entity\Property\ElasticIdInterface
	 */
	private $id;

	/**
	 * @var int
	 */
	private $databaseId;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string|null
	 */
	private $content;

	/**
	 * @var string
	 */
	private $alias;

	/**
	 * @var string
	 */
	private $image;

	/**
	 * @var float
	 */
	private $price;

	/**
	 * @var string
	 */
	private $availability;

	/**
	 * @var array
	 */
	private $tags;

	/**
	 * @var array
	 */
	private $categories;

	/**
	 * @var array
	 */
	private $purpose;

	/**
	 * @var int
	 */
	private $venality;

	/**
	 * @var string
	 */
	private $brand;


	public function __construct(
		\Spameri\Elastic\Entity\Property\ElasticIdInterface $id,
		int $databaseId,
		string $name,
		?string $content,
		string $alias,
		string $image,
		float $price,
		string $availability,
		array $tags,
		array $categories,
		array $purpose,
		int $venality,
		string $brand
	)
	{
		parent::__construct($id);
		$this->id = $id;
		$this->databaseId = $databaseId;
		$this->name = $name;
		$this->content = $content;
		$this->alias = $alias;
		$this->image = $image;
		$this->price = $price;
		$this->availability = $availability;
		$this->tags = $tags;
		$this->categories = $categories;
		$this->purpose = $purpose;
		$this->venality = $venality;
		$this->brand = $brand;
	}


	public function id(): \Spameri\Elastic\Entity\Property\ElasticIdInterface
	{
		return $this->id;
	}


	public function entityVariables(): array
	{
		return \get_object_vars($this);
	}


	public function getId(): \Spameri\Elastic\Entity\Property\ElasticIdInterface
	{
		return $this->id;
	}


	public function getDatabaseId(): int
	{
		return $this->databaseId;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getContent(): ?string
	{
		return $this->content;
	}


	public function getAlias(): string
	{
		return $this->alias;
	}


	public function getImage(): string
	{
		return $this->image;
	}


	public function getPrice(): float
	{
		return $this->price;
	}


	public function getAvailability(): string
	{
		return $this->availability;
	}


	public function getTags(): array
	{
		return $this->tags;
	}


	public function getCategories(): array
	{
		return $this->categories;
	}


	public function getPurpose(): array
	{
		return $this->purpose;
	}


	public function getVenality(): int
	{
		return $this->venality;
	}


	public function getBrand(): string
	{
		return $this->brand;
	}

}
