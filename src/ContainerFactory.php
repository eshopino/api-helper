<?php
namespace Eshopino\Api;

class ContainerFactory
{

	private function __construct()
	{

	}


	public static function create()
	{
		$container = new DiContainer();
		$container->registerService('application', function (DiContainer $container) {
			return new \Eshopino\Api\Application($container->getService('requestFactory'), $container->getService('controllerFactory'),
				$container->getService('configuration'));
		});
		$container->registerService('requestFactory', function (DiContainer $container) {
			return new \Eshopino\Api\RequestFactory($container->getService('configuration'));
		});

		$container->registerService('controllerFactory', function (DiContainer $container) {
			return new \Eshopino\Api\ControllerFactory($container);
		});

		$container->registerService('controller.OrderCreate', function (DiContainer $container) {
			return new \Eshopino\Api\Controllers\OrderCreateController($container->getService('itemManager'), $container->getService('orderManager'),
				$container->getService('configuration'));
		});
		$container->registerService('controller.OrderStatus', function (DiContainer $container) {
			return new \Eshopino\Api\Controllers\OrderStatusController($container->getService('orderManager'));
		});
		$container->registerService('controller.ItemStatus', function (DiContainer $container) {
			return new \Eshopino\Api\Controllers\ItemStatusController($container->getService('itemManager'));
		});

		$container->registerService('configuration', function (DiContainer $container) {
			return new \Eshopino\Api\Configuration();
		});


		return $container;
	}
}
