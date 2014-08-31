<?php

/**
 * Test: Zenify\DoctrineMethodsHydrator\Convertor.
 *
 * @testCase
 * @package Zenify\DoctrineMethodsHydrator
 */

namespace ZenifyTests\DoctrineMethodsHydrator;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Nette;
use Tester\Assert;
use Zenify;
use ZenifyTests\DoctrineMethodsHydrator\Entities\Product;
use ZenifyTests\DoctrineMethodsHydrator\UI\MockControl;
use ZenifyTests\DoctrineMethodsHydrator\UI\MockPresenter;


$container = require_once __DIR__ . '/../bootstrap.php';


class ConvertorTest extends BaseTestCase
{
	/** @var Nette\DI\Container */
	private $container;

	/** @var MockPresenter */
	private $presenter;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	protected function setUp()
	{
		$this->prepareDbData();
		$this->buildPresenter();
	}


	public function testTraits()
	{
		$usedTraits = class_uses($this->presenter);
		Assert::true(in_array('Zenify\DoctrineMethodsHydrator\Application\TTryCall', $usedTraits));
		Assert::true(method_exists($this->presenter, 'tryCall'));
		Assert::true(method_exists($this->presenter, 'getconvertor'));

		$control = $this->presenter['mockControl'];
		$usedTraits = class_uses($control);
		Assert::true(in_array('Zenify\DoctrineMethodsHydrator\Application\TTryCall', $usedTraits));
		Assert::true(method_exists($control, 'tryCall'));
		Assert::true(method_exists($control, 'getconvertor'));
	}


	public function testNotEntity()
	{
		Assert::exception(function() {
			$this->callPresenterAction($this->presenter, 'category', ['category' => 5]);
		}, 'Doctrine\ORM\Mapping\MappingException', 'Class "ZenifyTests\DoctrineMethodsHydrator\Entities\Category" sub class of "Nette\Object" is not a valid entity or mapped super class.');
	}


	public function testConvertor()
	{
		// render
		$this->callPresenterAction($this->presenter, 'product', ['product' => 1]);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::same($this->presenter->product->getId(), 1);

		$this->presenter->product = NULL;
		Assert::null($this->presenter->product);

		// action
		$this->callPresenterAction($this->presenter, 'edit', ['product' => 1]);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::same($this->presenter->product->getId(), 1);

		$this->presenter->product = NULL;
		Assert::null($this->presenter->product);

		// handle
		$this->callPresenterAction($this->presenter, 'default', [
			'do' => 'delete',
			'product' => 1
		]);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::same($this->presenter->product->getId(), 1);
	}


	public function testNoValue()
	{
		Assert::exception(function() {
			$this->callPresenterAction($this->presenter, 'product');
		}, 'Doctrine\ORM\ORMException', 'The identifier id is missing for a query of ZenifyTests\DoctrineMethodsHydrator\Entities\Product');
	}


	public function testNotExistingId()
	{
		Assert::exception(function() {
			$this->callPresenterAction($this->presenter, 'product', ['product' => 5]);
		}, 'Nette\Application\BadRequestException', 'Entity "ZenifyTests\DoctrineMethodsHydrator\Entities\Product" with id = "5" was not found.');
	}


	public function testComponent()
	{
		$mockControl = $this->presenter['mockControl'];
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\UI\MockControl', $mockControl);

		$convertor = $this->invokeMethod($mockControl, 'getConvertor');
		Assert::type('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor', $convertor);
	}


	private function prepareDbData()
	{
		/** @var Connection $connection */
		$connection = $this->container->getByType('Doctrine\DBAL\Connection');
		$connection->query('CREATE TABLE product (id INTEGER NOT NULL, name string, PRIMARY KEY(id))');

		$product = new Product;
		$product->setName('Brand new ruler');

		/** @var EntityManager $em */
		$em = $this->container->getByType('Doctrine\ORM\EntityManager');
		$em->persist($product);
		$em->flush();
	}


	private function buildPresenter()
	{
		$this->presenter = new MockPresenter;
		$this->container->callMethod(array($this->presenter, 'injectPrimary'));
	}

}


\run(new ConvertorTest($container));
