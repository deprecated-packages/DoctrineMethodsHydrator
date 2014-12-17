<?php

namespace Zenify\DoctrineMethodsHydrator\Tests;

use Nette;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use PHPUnit_Framework_TestCase;
use Zenify;
use Zenify\DoctrineMethodsHydrator\Tests\Entities\Product;
use Zenify\DoctrineMethodsHydrator\Tests\UI\MockPresenter;


class ParametersToEntitiesConvertorTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var MockPresenter
	 */
	private $presenter;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		/** @var DatabaseLoader $databaseLoader */
		$databaseLoader = $this->container->getByType(DatabaseLoader::class);
		$databaseLoader->loadProductTableWithOneItem();
		$this->presenter = $this->buildPresenter();
	}


	/**
	 * @expectedException \Doctrine\ORM\Mapping\MappingException
	 */
	public function testNotEntity()
	{
		$this->callPresenterAction($this->presenter, 'category', ['category' => 5]);
	}


	public function testMethodTypes()
	{
		// render
		$this->callPresenterAction($this->presenter, 'product', ['product' => 1]);
		$this->assertInstanceOf(Product::class, $this->presenter->product);
		$this->assertSame(1, $this->presenter->product->getId());

		$this->presenter->product = NULL;

		// action
		$this->callPresenterAction($this->presenter, 'edit', ['product' => 1]);
		$this->assertInstanceOf(Product::class, $this->presenter->product);
		$this->assertSame(1, $this->presenter->product->getId());

		$this->presenter->product = NULL;

		// handle
		$this->callPresenterAction($this->presenter, 'default', [
			'do' => 'delete',
			'product' => 1
		]);

		$this->assertInstanceOf(Product::class, $this->presenter->product);
		$this->assertSame(1, $this->presenter->product->getId());
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


	/**
	 * @param Presenter $presenter
	 * @param string $action
	 * @param array $args
	 * @return Nette\Application\IResponse
	 */
	private function callPresenterAction(Presenter $presenter, $action, $args = [])
	{
		$args['action'] = $action;
		$request = new Request($presenter->getName(), 'GET', $args);
		return $presenter->run($request);
	}

}
