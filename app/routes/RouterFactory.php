<?php
namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Object;

/**
 * @author David Matejka
 */
class RouterFactory extends Object
{

	/**
	 * @return RouteList
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('/', "List:default");
		$router[] = new Route('<locale=en en|cs>/<id>[/<action=default>]', "Detail:default");

		return $router;
	}
}