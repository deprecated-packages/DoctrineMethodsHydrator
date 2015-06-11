<?php

namespace Zenify\DoctrineMethodsHydrator\Tests\UI;

use Nette\Application\UI\Control;
use Zenify\DoctrineMethodsHydrator\Contract\MethodsHydratorInterface;


class MockControl extends Control
{

	/**
	 * @var MethodsHydratorInterface
	 */
	private $methodsHydrator;


	public function __construct(MethodsHydratorInterface $methodsHydrator)
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
