<?php
namespace Eshopino\Api;

/**
 * @author David Matejka
 */
class Response
{

	/** @var array */
	private $data;

	/** @var int */
	private $code;


	public function __construct($data, $code)
	{
		$this->data = $data;
		$this->code = $code;
	}


	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}


	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

}
