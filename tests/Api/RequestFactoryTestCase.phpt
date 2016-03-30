<?php
namespace EshopinoTest\Api;

use Eshopino\Api\AuthorizationException;
use Eshopino\Api\Configuration;
use Eshopino\Api\RequestFactory;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 * @testCase
 */
class RequestFactoryTestCase extends Tester\TestCase
{

	public function setUp()
	{
	}


	public function testGet()
	{
		$config = new Configuration();
		$config->setPublicKey('abc');
		$config->setPrivateKey('xyz');
		$requestFactory = new RequestFactory($config);
		$serverBackup = $_SERVER;

		$_SERVER['REQUEST_URI'] = '/order';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['HTTP_X_API_KEY'] = 'abc';
		$_SERVER['HTTP_X_API_SIGNATURE'] = hash_hmac('sha256', '/order', 'xyz');
		$response = $requestFactory->createRequest();
		Assert::same('order', $response->getName());
		Assert::same('GET', $response->getMethod());

		$_SERVER = $serverBackup;
	}


	public function testDifferentBasepath()
	{
		$config = new Configuration();
		$config->setPublicKey('abc');
		$config->setPrivateKey('xyz');
		$config->setBasePath('/foo');
		$requestFactory = new RequestFactory($config);
		$serverBackup = $_SERVER;

		$_SERVER['REQUEST_URI'] = '/foo/order';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['HTTP_X_API_KEY'] = 'abc';
		$_SERVER['HTTP_X_API_SIGNATURE'] = hash_hmac('sha256', '/order', 'xyz');
		$response = $requestFactory->createRequest();
		Assert::same('order', $response->getName());
		Assert::same('GET', $response->getMethod());

		$_SERVER = $serverBackup;
	}


	public function testPost()
	{
		$config = new Configuration();
		$config->setPublicKey('abc');
		$config->setPrivateKey('xyz');
		$requestFactory = new RequestFactory($config);
		$serverBackup = $_SERVER;

		$_SERVER['REQUEST_URI'] = '/order';
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['HTTP_X_API_KEY'] = 'abc';
		$data = json_encode(['foo' => 'bar']);
		$_SERVER['HTTP_X_API_SIGNATURE'] = hash_hmac('sha256', '/order' . $data, 'xyz');
		stream_wrapper_unregister("php");
		stream_wrapper_register("php", "EshopinoTest\\MockPhpStream");
		file_put_contents('php://input', $data);

		$response = $requestFactory->createRequest();
		stream_wrapper_restore("php");
		Assert::same('order', $response->getName());
		Assert::same('POST', $response->getMethod());
		Assert::same(['foo' => 'bar'], $response->getParameters());

		$_SERVER = $serverBackup;
	}


	public function testInvalidPublic()
	{
		$config = new Configuration();
		$config->setPublicKey('abc');
		$config->setPrivateKey('xyz');
		$requestFactory = new RequestFactory($config);
		$serverBackup = $_SERVER;
		$_SERVER['REQUEST_URI'] = '/order';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['HTTP_X_API_KEY'] = 'aaa';
		Assert::exception(function () use ($requestFactory) {
			$requestFactory->createRequest();
		}, "Eshopino\\Api\\AuthorizationException", "Invalid API key");
		$_SERVER = $serverBackup;
	}


	public function testInvalidSig()
	{
		$config = new Configuration();
		$config->setPublicKey('abc');
		$config->setPrivateKey('xyz');
		$requestFactory = new RequestFactory($config);
		$serverBackup = $_SERVER;
		$_SERVER['REQUEST_URI'] = '/order';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['HTTP_X_API_KEY'] = 'abc';
		$_SERVER['HTTP_X_API_SIGNATURE'] = hash_hmac('sha256', '/order', 'xxx');
		Assert::exception(function () use ($requestFactory) {
			$requestFactory->createRequest();
		}, "Eshopino\\Api\\AuthorizationException", "Invalid signature");
		$_SERVER = $serverBackup;
	}

}


(new RequestFactoryTestCase())->run();
