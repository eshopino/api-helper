<?php
namespace Eshopino\Api;

class Application
{

	/** @var ControllerFactory */
	private $controllerFactory;

	/** @var RequestFactory */
	private $requestFactory;

	/** @var Configuration */
	private $configuration;


	public function __construct(RequestFactory $requestFactory, ControllerFactory $controllerFactory, Configuration $configuration)
	{
		$this->controllerFactory = $controllerFactory;
		$this->requestFactory = $requestFactory;
		$this->configuration = $configuration;
	}


	public function run()
	{
		try {
			$request = $this->requestFactory->createRequest();
			$controller = $this->controllerFactory->create($request);
			$response = $controller->run($request);
		} catch (AuthorizationException $e) {
			$response = new Response(['message' => $e->getMessage()], 401);
		} catch (ApiException $e) {
			$response = new Response(['message' => $e->getMessage()], $e->getCode() ?: 400);
		}

		http_response_code($response->getCode());
		header('Content-Type: application/json');
		$body = json_encode($response->getData());
		header('X-Api-Signature: ' . hash_hmac('sha256', $body, $this->configuration->getPrivateKey()));

		echo $body;
	}

}
