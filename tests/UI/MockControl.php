<?php

namespace ZenifyTests\DoctrineMethodsHydrator\UI;

use Nette;
use Zenify;


class MockControl extends Nette\Application\UI\Control
{

	/**
	 * @var \Zenify\DoctrineMethodsHydrator\MethodsHydrator
	 */
	private $methodsHydrator;


	public function __construct(Zenify\DoctrineMethodsHydrator\MethodsHydrator $methodsHydrator)
	{
		$this->methodsHydrator = $methodsHydrator;
	}


	/**
	 * @param string $method
	 * @param array $parameters
	 * @return bool
	 */
	protected function tryCall($method, array $parameters)
	{
		return $this->methodsHydrator->hydrate($method, $parameters, $this);
	}

}
