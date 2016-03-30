<?php
namespace Eshopino\Api;

/**
 * @author David Matejka
 */
class ControllerFactory
{
	/** @var DiContainer */
	private $container;


	public function __construct(DiContainer $container)
	{
		$this->container = $container;
	}


	/**
	 * @param Request $request
	 * @return IController
	 * @throws ApiException
	 */
	public function create(Request $request)
	{
		$name = $this->getName($request);

		return $this->container->createService('controller.' . $name);
	}


	private function getName(Request $request)
	{
		$name = $request->getName();
		$method = $request->getMethod();

		if ($name === 'order' && $method === Request::POST) {
			return 'OrderCreate';
		} elseif ($name === 'order' && $method === Request::GET) {
			return 'OrderStatus';
		} elseif ($name === 'product' && $method === Request::GET) {
			return 'ItemStatus';
		}
		throw new InvalidRequestException("Invalid request: $method $name");
	}
}
