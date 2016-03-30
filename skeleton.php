<?php
//require __DIR__ . '/eshopino.phar'; //pro phar archive
require __DIR__ . '/vendor/autoload.php'; //pro composer

/** @var \Eshopino\Api\DiContainer $container */
$container = \Eshopino\Api\ContainerFactory::create();
$configuration = $container->getConfiguration();
$configuration->setPublicKey('verejny klic');
$configuration->setPrivateKey('privatni klic');
$configuration->setBasePath('/'); //pokud je na /, tak ani neni nutno uvadet
//$configuration->setBasePath('/api'); //pokud je API v podadresari
$configuration->setToleration(1, 0.01); //tolerance chyby v cene je 1% + 1 Kc


class MyItemManager implements \Eshopino\Api\Model\Item\IItemManager
{
	/**
	 * @param string|int|null $productCode
	 * @param string|int|null $variantCode
	 * @return \Eshopino\Api\Model\Item\ItemStatus
	 */
	public function find($productCode, $variantCode)
	{
		//todo: implement
	}


}


class MyOrderManager implements \Eshopino\Api\Model\Order\IOrderManager
{

	/**
	 * @param string|int $id
	 * @return \Eshopino\Api\Model\Order\OrderStatus|null v pripade, ze objednavka neexistuje, vratit NULL
	 */
	public function getStatus($id)
	{
		//todo: implement
	}


	/**
	 * @param array pole s objednavkou, shodne jako v API doc
	 * @throws \Eshopino\Api\ValidationException pokud jsou nektere udaje nespravne, metoda vyhodi tuto vyjimku. Stav produktu jiz neni nutno kontrolovat
	 * @return \Eshopino\Api\Model\Order\OrderCreationResult
	 */
	public function create($order)
	{
		//todo: implement
	}

}


$container->registerItemManager(new MyItemManager());
$container->registerOrderManager(new MyOrderManager());

try {
	$container->getApplication()->run();
} catch (\Exception $e) {
	http_response_code(500);
	echo json_encode([
		'message' => 'Internal error',
		'exception' => (string) $e, //optional
	]);
	//todo log exception
}

