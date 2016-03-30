<?php
namespace Eshopino\Api;

/**
 * @author David Matejka
 */
interface IController
{

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request);

}
