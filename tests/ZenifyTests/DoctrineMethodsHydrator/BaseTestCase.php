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


	/**
	 * Call protected/private method of a class.
	 * @source https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
	 *
	 * @param object|Nette\Object  &$object    Instantiated object that we will run method on.
	 * @param string  $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = [])
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
