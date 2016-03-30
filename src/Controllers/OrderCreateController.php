<?php
namespace Eshopino\Api\Controllers;

use Eshopino\Api\Configuration;
use Eshopino\Api\IController;
use Eshopino\Api\InvalidRequestException;
use Eshopino\Api\Model\Item\IItemManager;
use Eshopino\Api\Model\Order\IOrderManager;
use Eshopino\Api\Request;
use Eshopino\Api\Response;
use Eshopino\Api\ValidationException;

class OrderCreateController implements IController
{

	/** @var Configuration */
	private $configuration;

	/** @var IItemManager */
	private $itemManager;

	/** @var \Eshopino\Api\Model\Order\IOrderManager */
	private $orderManager;


	public function __construct(IItemManager $itemManager, IOrderManager $orderManager, Configuration $configuration)
	{

		$this->configuration = $configuration;
		$this->itemManager = $itemManager;
		$this->orderManager = $orderManager;
	}


	public function run(Request $request)
	{
		$errors = $this->validate($request);
		if (count($errors)) {
			return new Response(['errors' => $errors], 400);
		}

		try {
			$result = $this->orderManager->create($request->getParameters());
		} catch (ValidationException $e) {
			throw new InvalidRequestException($e->getMessage(), 400, $e);
		}

		return new Response([
			'message' => 'OK',
			'data' => [
				'id' => $result->getId(),
				'variableSymbol' => $result->getVariableSymbol()
			]
		], $result->isDuplicate() ? 409 : 201);

	}


	private function priceDiffers($requestedPrice, $actualPrice)
	{
		$diff = abs($requestedPrice - $actualPrice);

		return $diff > $this->configuration->calculateToleration($requestedPrice);
	}


	/**
	 * @param Request $request
	 * @return array
	 */
	private function validate(Request $request)
	{
		$errors = [];
		$parameters = $request->getParameters();
		foreach ($parameters['items'] as $item) {
			$responseData = array_intersect_key($item, ['product' => NULL, 'variant' => NULL]);
			$item += ['variant' => NULL, 'product' => NULL];
			$status = $this->itemManager->find($item['product'], $item['variant']);
			if (!$status || $status->getQuantity() == 0) {
				$errors[] = [
					'code' => 1100,
					'parameters' => $responseData,
				];
			} elseif ($status->getQuantity() < $item['quantity']) {
				$errors[] = [
					'code' => 1101,
					'parameters' => $responseData + [
							'availableQuantity' => $status->getQuantity(),
						],
				];
			}
			if ($status && $this->priceDiffers($item['unitPrice'], $status->getPrice())) {
				$errors[] = [
					'code' => 1102,
					'parameters' => $responseData + [
							'actualPrice' => $status->getPrice(),
						],
				];
			}
		}

		return $errors;
	}

}
