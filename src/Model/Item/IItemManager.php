<?php
namespace Eshopino\Api\Model\Item;

/**
 * @author David Matejka
 */
interface IItemManager
{

	/**
	 * @param string|int|null $productCode
	 * @param string|int|null $variantCode
	 * @return ItemStatus|null
	 */
	public function find($productCode, $variantCode);

}
