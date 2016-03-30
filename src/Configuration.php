<?php
namespace Eshopino\Api;

/**
 * @author David Matejka
 */
class Configuration
{

	/** @var string */
	private $publicKey;

	/** @var string */
	private $privateKey;

	/** @var string */
	private $basePath = '/';

	/** @var float */
	private $tolerationRelative = 0;

	/** @var float */
	private $tolerationAbsolute = 1;


	/**
	 * @return string
	 */
	public function getPrivateKey()
	{
		return $this->privateKey;
	}


	/**
	 * @param string
	 */
	public function setPrivateKey($privateKey)
	{
		$this->privateKey = $privateKey;
	}


	/**
	 * @return string
	 */
	public function getPublicKey()
	{
		return $this->publicKey;
	}


	/**
	 * @param string
	 */
	public function setPublicKey($publicKey)
	{
		$this->publicKey = $publicKey;
	}


	/**
	 * @return string
	 */
	public function getBasePath()
	{
		return $this->basePath;
	}


	/**
	 * @param string
	 */
	public function setBasePath($basePath)
	{
		$basePath = trim($basePath, '/');
		$this->basePath = $basePath ? '/' . $basePath . '/' : '/';
	}


	public function setToleration($absolute, $relative)
	{
		$this->tolerationRelative = $relative;
		$this->tolerationAbsolute = $absolute;
	}


	public function calculateToleration($price)
	{
		return $price * $this->tolerationRelative + $this->tolerationAbsolute;
	}

}
