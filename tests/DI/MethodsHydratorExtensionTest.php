<?php

namespace ZenifyTests\DoctrineMethodsHydrator\DI;

use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMethodsHydrator\Contract\Doctrine\ParametersToEntitiesConvertorInterface;
use Zenify\DoctrineMethodsHydrator\Contract\MethodsHydratorInterface;
use Zenify\DoctrineMethodsHydrator\Doctrine\ParametersToEntitiesConvertor;
use Zenify\DoctrineMethodsHydrator\MethodsHydrator;
use Zenify\DoctrineMethodsHydrator\Tests\ContainerFactory;


class MethodsHydratorExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	protected function setUp()
	{
		$this->container = (new ContainerFactory)->create();
	}


	public function testExtension()
	{
		$this->assertInstanceOf(
			ParametersToEntitiesConvertor::class,
			$this->container->getByType(ParametersToEntitiesConvertorInterface::class)
		);

		$this->assertInstanceOf(
			MethodsHydrator::class,
			$this->container->getByType(MethodsHydratorInterface::class)
		);
	}

}
