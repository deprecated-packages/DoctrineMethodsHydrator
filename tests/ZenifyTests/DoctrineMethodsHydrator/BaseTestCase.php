<?php

namespace ZenifyTests\DoctrineMethodsHydrator;

use Nette;
use Tester\TestCase;


class BaseTestCase extends TestCase
{

	/**
	 * Simulates request calling presenter's action
	 * @param Nette\Application\UI\Presenter $presenter
	 * @param string $action
	 * @param array $args
	 * @return Nette\Application\IResponse
	 */
	protected function callPresenterAction($presenter, $action, $args = array())
	{
		$args['action'] = $action;
		$request = new Nette\Application\Request($presenter->getName(), 'GET', $args);
		return $presenter->run($request);
	}

}
