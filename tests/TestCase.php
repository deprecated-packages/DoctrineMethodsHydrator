<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace ZenifyTests;

use Nette;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use Tester;


class TestCase extends Tester\TestCase
{

	/**
	 * Simulates request calling presenter's action
	 *
	 * @param Presenter $presenter
	 * @param string $action
	 * @param array $args
	 * @return Nette\Application\IResponse
	 */
	public function callPresenterAction(Presenter $presenter, $action, $args = array())
	{
		$args['action'] = $action;
		$request = new Request($presenter->getName(), 'GET', $args);
		return $presenter->run($request);
	}


	/**
	 * Call protected/private method of a class.
	 * @source https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
	 *
	 * @param object|Nette\Object &$object Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array $parameters Array of parameters to pass into method.
	 * @return mixed
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
		if ($object instanceof Nette\Object) {
			/** @var Nette\Object $object */
			$reflection = $object->getReflection();

		} else {
			$reflection = new \ReflectionClass(get_class($object));
		}

		$method = $reflection->getMethod($methodName);
		$method->setAccessible(TRUE);

		return $method->invokeArgs($object, $parameters);
	}

}
