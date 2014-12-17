<?php

namespace ZenifyTests\DoctrineMethodsHydrator\DI;

use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMethodsHydrator\Doctrine\ParametersToEntitiesConvertor;
use Zenify\DoctrineMethodsHydrator\MethodsHydrator;
use Zenify\DoctrineMethodsHydrator\Tests\ContainerFactory;


class MethodsHydratorExtensionTest extends PHPUnit_Framework_TestCase
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
		$this->assertInstanceOf(
			ParametersToEntitiesConvertor::class,
			$this->container->getByType(ParametersToEntitiesConvertor::class)
		);

		$this->assertInstanceOf(
			MethodsHydrator::class,
			$this->container->getByType(MethodsHydrator::class)
		);
	}

}
