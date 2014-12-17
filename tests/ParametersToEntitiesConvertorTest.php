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


$container = require_once __DIR__ . '/../bootstrap.php';


class ParametersToEntitiesConvertorTest extends TestCase
{

	/**
	 * @var bool
	 */
	private $isDbPrepared = FALSE;

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
		$this->presenter = $this->buildPresenter();
		$this->prepareDbData();
	}


	/**
	 * @throws \Doctrine\ORM\Mapping\MappingException
	 */
	public function testNotEntity()
	{
		$this->callPresenterAction($this->presenter, 'category', ['category' => 5]);
	}


	public function testMethodTypes()
	{
		// render
		$this->callPresenterAction($this->presenter, 'product', ['product' => 1]);
		Assert::type(
			'ZenifyTests\DoctrineMethodsHydrator\Entities\Product',
			$this->presenter->product
		);
		Assert::same(1, $this->presenter->product->getId());

		$this->presenter->product = NULL;

		// action
		$this->callPresenterAction($this->presenter, 'edit', ['product' => 1]);
		Assert::type(
			'ZenifyTests\DoctrineMethodsHydrator\Entities\Product',
			$this->presenter->product
		);
		Assert::same(1, $this->presenter->product->getId());

		$this->presenter->product = NULL;

		// handle
		$this->callPresenterAction($this->presenter, 'default', [
			'do' => 'delete',
			'product' => 1
		]);
		Assert::type(
			'ZenifyTests\DoctrineMethodsHydrator\Entities\Product',
			$this->presenter->product
		);
		Assert::same(1, $this->presenter->product->getId());
	}


	public function testNoValue()
	{
		$presenter = $this->presenter;
		$that = $this;
		Assert::error(function() use ($presenter, $that) {
			$that->callPresenterAction($presenter, 'product');
		}, E_RECOVERABLE_ERROR);
	}


	public function testNoValueOptional()
	{
		$this->callPresenterAction($this->presenter, 'productOptional');
		Assert::same($this->presenter->product, NULL);
	}


	/**
	 * @throws \Nette\Application\BadRequestException
	 */
	public function testNotExistingId()
	{
		$this->callPresenterAction($this->presenter, 'product', ['product' => 5]);
	}


	public function testComponent()
	{
		$mockControl = $this->presenter['mockControl'];
		Assert::type(
			'ZenifyTests\DoctrineMethodsHydrator\UI\MockControl',
			$mockControl
		);
	}


	private function prepareDbData()
	{
		if ($this->isDbPrepared) {
			return;
		}

		/** @var Connection $connection */
		$connection = $this->container->getByType('Doctrine\DBAL\Connection');
		$connection->query('CREATE TABLE product (id INTEGER NOT NULL, name string, PRIMARY KEY(id))');

		$product = new Product;
		$product->setName('Brand new ruler');

		/** @var EntityManager $em */
		$em = $this->container->getByType('Doctrine\ORM\EntityManager');
		$em->persist($product);
		$em->flush();

		$this->isDbPrepared = TRUE;
	}


	/**
	 * @return MockPresenter
	 */
	private function buildPresenter()
	{
		$presenter = new MockPresenter;
		$this->container->callInjects($presenter);
		return $presenter;
	}

}


(new ParametersToEntitiesConvertorTest($container))->run();
