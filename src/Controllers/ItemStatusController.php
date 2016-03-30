<?php
namespace Eshopino\Api\Controllers;

use Eshopino\Api\IController;
use Eshopino\Api\Model\Item\IItemManager;
use Eshopino\Api\Request;
use Eshopino\Api\Response;

/**
 * @author David Matejka
 */
class ItemStatusController implements IController
{

	/** @var IItemManager */
	private $itemManager;


	public function __construct(IItemManager $itemManager)
	{
		$this->itemManager = $itemManager;
	}


	public function run(Request $request)
	{
		$result = [];
		foreach ($request->getParameters()['item'] as $item) {
			$item += ['product' => NULL, 'variant' => NULL];
			$status = $this->itemManager->find($item['product'], $item['variant']);
			if ($status === NULL || $status->getQuantity() === 0) {
				continue;
			}
			$result[] = [
					'availability' => $status->getAvailability(),
					'quantity' => $status->getQuantity(),
					'price' => $status->getPrice(),
				] + $item;
		}

		return new Response(['items' => $result], 200);
	}

}
