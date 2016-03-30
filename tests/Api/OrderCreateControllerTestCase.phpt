<?php
namespace EshopinoTest\Api;

use Eshopino\Api\Configuration;
use Eshopino\Api\Controllers\OrderCreateController;
use Eshopino\Api\Model\Item\ItemStatus;
use Eshopino\Api\Model\Order\OrderCreationResult;
use Eshopino\Api\Request;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 * @testCase
 */
class OrderCreateControllerTestCase extends Tester\TestCase
{

	public function setUp()
	{
	}


	/**
	 * @dataProvider getValidationErrorData
	 */
	public function testValidationErrors($status, $errors)
	{
		$itemManager = \Mockery::mock('Eshopino\Api\Model\Item\IItemManager')
			->shouldReceive('find')->with(1, 2)->andReturn($status)
			->getMock();
		$orderManager = \Mockery::mock('\Eshopino\Api\Model\Order\IOrderManager');
		$config = new Configuration();
		$controller = new OrderCreateController($itemManager, $orderManager, $config);
		$result = $controller->run(new Request('order', 'POST', [
			'items' => [
				['product' => 1, 'variant' => 2, 'quantity' => 2, 'unitPrice' => 10]
			],
		]));
		Assert::same([
			'errors' => [
				$errors,
			]
		], $result->getData());
		Assert::same(400, $result->getCode());
	}


	public function getValidationErrorData()
	{
		return [
			[
				NULL,
				['code' => 1100, 'parameters' => ['product' => 1, 'variant' => 2,]],
			],
			[
				new ItemStatus(0, 10, 0),
				['code' => 1100, 'parameters' => ['product' => 1, 'variant' => 2,]],
			],
			[
				new ItemStatus(0, 10, 1),
				['code' => 1101, 'parameters' => ['product' => 1, 'variant' => 2, 'availableQuantity' => 1,]],
			],
			[
				new ItemStatus(0, 20, 2),
				['code' => 1102, 'parameters' => ['product' => 1, 'variant' => 2, 'actualPrice' => 20,]],
			],

		];
	}


	public function testOk()
	{
		$itemManager = \Mockery::mock('Eshopino\Api\Model\Item\IItemManager')
			->shouldReceive('find')->with(1, 2)->andReturn(new ItemStatus(0, 10, 1))
			->getMock();
		$orderManager = \Mockery::mock('\Eshopino\Api\Model\Order\IOrderManager');
		$input = [
			'items' => [
				['product' => 1, 'variant' => 2, 'quantity' => 1, 'unitPrice' => 11]
			],
		];
		$orderManager->shouldReceive('create')->with($input)->andReturn(new OrderCreationResult(123, 456));
		$config = new Configuration();
		$config->setToleration(1, 0);
		$controller = new OrderCreateController($itemManager, $orderManager, $config);
		$result = $controller->run(new Request('order', 'POST', $input));


		Assert::same([
			'message' => 'OK',
			'data' => [
				'id' => 123,
				'variableSymbol' => 456
			],
		], $result->getData());
		Assert::same(201, $result->getCode());
	}

}


(new OrderCreateControllerTestCase())->run();
