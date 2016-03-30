<?php
namespace Eshopino\Api\Model\Order;

/**
 * @author David Matejka
 */
class OrderCreationResult
{

	/** @var bool */
	private $duplicate;

	/** @var int|string */
	private $id;

	/** @var int */
	private $variableSymbol;


	/**
	 * @param int|string $id vas identifikator objednavky. Pod nim budou chodit dotazy na stav
	 * @param int $variableSymbol variabilni symbol pro platbu. obvykle shodny s cislem objednavky
	 * @param bool $duplicate aplikace by si mela zapamatovat eshopinoId z pozadavku a nevytvaret pote objednavku znovu a vratit informace o stavajici. V takovem pripade je nutno nastavit $duplicate na true
	 */
	public function __construct($id, $variableSymbol, $duplicate = FALSE)
	{
		$this->duplicate = $duplicate;
		$this->id = $id;
		$this->variableSymbol = $variableSymbol;
	}


	/**
	 * @return boolean
	 */
	public function isDuplicate()
	{
		return $this->duplicate;
	}


	/**
	 * @return int|string
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return int
	 */
	public function getVariableSymbol()
	{
		return $this->variableSymbol;
	}


}
