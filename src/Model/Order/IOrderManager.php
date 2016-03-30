<?php
namespace Eshopino\Api\Model\Order;

use Eshopino\Api\ValidationException;

/**
 * @author David Matejka
 */
interface IOrderManager
{

	/**
	 * @param string|int $id
	 * @return OrderStatus|null v pripade, ze objednavka neexistuje, vratit NULL
	 */
	public function getStatus($id);


	/**
	 * @param array pole s objednavkou, shodne jako v API doc
	 * @throws ValidationException pokud jsou nektere udaje nespravne, metoda vyhodi tuto vyjimku. Stav produktu jiz neni nutno kontrolovat
	 * @return OrderCreationResult
	 */
	public function create($order);

}
