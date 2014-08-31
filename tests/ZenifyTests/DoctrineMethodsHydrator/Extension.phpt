<?php

/**
 * Test: Zenify\DoctrineMethodsHydrator\Extension.
 *
 * @testCase
 * @package Zenify\DoctrineMethodsHydrator
 */

namespace ZenifyTests\DoctrineMethodsHydrator;

use Nette;
use Tester\Assert;
use Zenify;


$container = require_once __DIR__ . '/../bootstrap.php';


class ExtensionTest extends BaseTestCase
{
	/** @var Nette\DI\Container */
	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function testExtension()
	{
		$convertor = $this->container->getByType('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor');
		Assert::type('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor', $convertor);
	}

}


\run(new ExtensionTest($container));
