<?php
namespace Eshopino\Api;

use Eshopino\Api\Model\Item\IItemManager;
use Eshopino\Api\Model\Order\IOrderManager;

/**
 * @author David Matejka
 */
class DiContainer
{
	private $factories = [];

	private $services = [];


	public function registerService($name, callable $callback)
	{
		$this->factories[$name] = $callback;
	}


	public function createService($name)
	{
		if (!isset($this->factories[$name])) {
			throw new \Exception("Service $name not found");
		}

		return call_user_func($this->factories[$name], $this);
	}


	public function getService($name)
	{
		if (!isset($this->services[$name])) {
			$this->services[$name] = $this->createService($name);
		}

		return $this->services[$name];
	}


	/**
	 * @return Configuration
	 */
	public function getConfiguration()
	{
		return $this->getService('configuration');
	}


	/**
	 * @return Application
	 */
	public function getApplication()
	{
		return $this->getService('application');
	}


	public function registerOrderManager(IOrderManager $orderManager)
	{
		$this->services['orderManager'] = $orderManager;
	}

	public function registerItemManager(IItemManager $itemManager)
	{
		$this->services['itemManager'] = $itemManager;
	}

}
