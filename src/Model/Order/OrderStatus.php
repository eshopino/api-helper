<?php
namespace Eshopino\Api\Model\Order;

/**
 * @author David Matejka
 */
class OrderStatus
{

	const CONFIRMED = 'confirmed';
	const CANCELLED = 'cancelled';
	const FINISHED = 'finished';

	/** @var string */
	private $note;

	/** @var string */
	private $status;

	/** @var \DateTimeInterface */
	private $dispatchedAt;

	/** @var float */
	private $conversionValue;


	/**
	 * @param string $status one of OrderStatus constants - CONFIRMED, CANCELLED, FINISHED
	 * @param string $note
	 * @param float $conversionValue
	 * @param \DateTimeInterface|NULL $dispatchedAt
	 */
	public function __construct($status, $note, $conversionValue, \DateTimeInterface $dispatchedAt = NULL)
	{
		$this->note = $note;
		$this->status = $status;
		$this->dispatchedAt = $dispatchedAt;
		$this->conversionValue = $conversionValue;
	}


	/**
	 * @return \DateTimeInterface
	 */
	public function getDispatchedAt()
	{
		return $this->dispatchedAt;
	}


	/**
	 * @return string
	 */
	public function getNote()
	{
		return $this->note;
	}


	/**
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
	}


	/**
	 * @return float
	 */
	public function getConversionValue()
	{
		return $this->conversionValue;
	}

}
