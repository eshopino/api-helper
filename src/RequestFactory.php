<?php
namespace Eshopino\Api;

/**
 * @author David Matejka
 */
class RequestFactory
{

	/** @var Configuration */
	private $configuration;


	public function __construct(Configuration $configuration)
	{
		$this->configuration = $configuration;
	}


	public function createRequest()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$basePath = $this->configuration->getBasePath();
		if ($basePath && strncmp($uri, $basePath, strlen($basePath)) !== 0) {
			throw new ApiException("Invalid endpoint");
		}
		$uri = substr($uri, strlen($basePath) - 1);
		if ($this->configuration->getPublicKey() !== trim($_SERVER['HTTP_X_API_KEY'])) {
			throw new AuthorizationException("Invalid API key");
		}
		$hasBody = $this->hasBody();
		$input = $hasBody ? file_get_contents('php://input') : '';
		$signature = hash_hmac('sha256', $uri . $input, $this->configuration->getPrivateKey());
		if ($signature !== trim($_SERVER['HTTP_X_API_SIGNATURE'])) {
			throw new AuthorizationException("Invalid signature");
		}

		if ($hasBody) {
			$parameters = json_decode($input, JSON_OBJECT_AS_ARRAY);
			if ($parameters === NULL && $input !== '' && strcasecmp(trim($input, " \t\n\r"), 'null') !== 0) {
				$error = json_last_error();
				throw new ApiException('JSON parsing error: ' . $error);
			}
		} else {
			$parameters = filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW);
		}
		$name = ($a = strpos($uri, '?')) !== FALSE ? substr($uri, 0, $a) : $uri;

		return new Request(ltrim($name, '/'), $_SERVER['REQUEST_METHOD'], $parameters);
	}


	private function hasBody()
	{
		return in_array($_SERVER['REQUEST_METHOD'], [Request::POST, Request::PUT], TRUE);
	}

}
