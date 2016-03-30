<?php
namespace Eshopino\Api\Model\Item;

/**
 * @author David Matejka
 */
class ItemStatus
{

	/** @var int in days */
	private $availability;

	/** @var float */
	private $price;

	/** @var int */
	private $quantity;


	/**
	 * @param int in days $availability
	 * @param float $price
	 * @param int $quantity
	 */
	public function __construct($availability, $price, $quantity)
	{
		$this->availability = $availability;
		$this->price = $price;
		$this->quantity = $quantity;
	}


	/**
	 * @return int
	 */
	public function getAvailability()
	{
		return $this->availability;
	}


	/**
	 * @return float
	 */
	public function getPrice()
	{
		return $this->price;
	}


	/**
	 * @return int
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}


}
