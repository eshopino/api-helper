<?php
namespace Eshopino\Api;

/**
 * @author David Matejka
 */
class Request
{

	const POST = 'POST';
	const PUT = 'PUT';
	const GET = 'GET';

	/** @var string */
	private $method;

	/** @var string */
	private $name;

	/** @var string */
	private $parameters;


	public function __construct($name, $method, $parameters)
	{
		$this->method = $method;
		$this->name = $name;
		$this->parameters = $parameters;
	}


	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getParameters()
	{
		return $this->parameters;
	}


}
