<?php

namespace ZenifyTests\DoctrineMethodsHydrator\DI;

use Nette\DI\Container;


class MethodsHydratorExtensionTest
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	public function testExtension()
	{
		Assert::type(
			'Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor',
			$this->container->getByType('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor')
		);

		Assert::type(
			'Zenify\DoctrineMethodsHydrator\MethodsHydrator',
			$this->container->getByType('Zenify\DoctrineMethodsHydrator\MethodsHydrator')
		);
	}

}
