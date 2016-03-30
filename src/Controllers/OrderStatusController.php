<?php
namespace Eshopino\Api\Controllers;

use Eshopino\Api\IController;
use Eshopino\Api\Model\Order\IOrderManager;
use Eshopino\Api\Request;
use Eshopino\Api\Response;

/**
 * @author David Matejka
 */
class OrderStatusController implements IController
{

	/** @var \Eshopino\Api\Model\Order\IOrderManager */
	private $orderManager;


	public function __construct(IOrderManager $orderManager)
	{
		$this->orderManager = $orderManager;
	}


	public function run(Request $request)
	{
		$result = [];
		$ids = $request->getParameters()['id'];
		foreach ($ids as $id) {
			$orderStatus = $this->orderManager->getStatus($id);
			if (!$orderStatus) {
				continue;
			}
			$result[$id] = [
				'status' => $orderStatus->getStatus(),
				'dispatchDate' => $orderStatus->getDispatchedAt() ? $orderStatus->getDispatchedAt()->format('Y-m-d H:i') : NULL,
				'note' => $orderStatus->getNote(),
				'conversionValue' => $orderStatus->getConversionValue(),
			];
		}

		return new Response(['orders' => $result], 200);
	}

}
