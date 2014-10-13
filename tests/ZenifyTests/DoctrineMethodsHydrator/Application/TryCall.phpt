<?php

namespace ZenifyTests\DoctrineMethodsHydrator;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Nette;
use Tester\Assert;
use Zenify;
use ZenifyTests\DoctrineMethodsHydrator\Entities\Product;
use ZenifyTests\DoctrineMethodsHydrator\UI\MockPresenter;
use ZenifyTests\TestCase;


$container = require_once __DIR__ . '/../../bootstrap.php';


class TryCallTest extends TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var MockPresenter
	 */
	private $presenter;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	protected function setUp()
	{
		$this->presenter = new MockPresenter();
		$this->container->callMethod(array($this->presenter, 'injectPrimary'));
	}


	public function testTraits()
	{
		$usedTraits = class_uses($this->presenter);
		Assert::true(in_array('Zenify\DoctrineMethodsHydrator\Application\TryCall', $usedTraits));
		Assert::true(method_exists($this->presenter, 'tryCall'));
		Assert::true(method_exists($this->presenter, 'getMethodsHydrator'));

		$control = $this->presenter['mockControl'];
		$usedTraits = class_uses($control);
		Assert::true(in_array('Zenify\DoctrineMethodsHydrator\Application\TryCall', $usedTraits));
		Assert::true(method_exists($control, 'tryCall'));
		Assert::true(method_exists($control, 'getMethodsHydrator'));
	}

}


\run(new TryCallTest($container));
