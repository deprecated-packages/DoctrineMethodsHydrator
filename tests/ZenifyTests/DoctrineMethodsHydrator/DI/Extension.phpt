<?php

namespace ZenifyTests\DoctrineMethodsHydrator\DI;

use Nette\DI\Container;
use Tester\Assert;
use ZenifyTests\TestCase;


$container = require_once __DIR__ . '/../../bootstrap.php';


class ExtensionTest extends TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
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


\run(new ExtensionTest($container));
