<?php

namespace Zenify\DoctrineMethodsHydrator\Tests;

use Doctrine\ORM\Mapping\MappingException;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMethodsHydrator\Tests\Entity\Product;
use Zenify\DoctrineMethodsHydrator\Tests\UI\MockPresenter;


final class ParametersToEntitiesConvertorTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var MockPresenter
	 */
	private $presenter;


	protected function setUp()
	{
		$this->container = (new ContainerFactory)->create();

		/** @var DatabaseLoader $databaseLoader */
		$databaseLoader = $this->container->getByType(DatabaseLoader::class);
		$databaseLoader->loadProductTableWithOneItem();
		$this->presenter = $this->buildPresenter();
	}


	public function testNotEntity()
	{
		$this->setExpectedException(MappingException::class);
		$this->callPresenterAction($this->presenter, 'category', ['category' => 5]);
	}


	public function testHandle()
	{
		$this->callPresenterAction($this->presenter, 'default', [
			'do' => 'delete',
			'product' => 1
		]);

		$this->validateProduct($this->presenter->getProduct());
	}


	public function testRender()
	{
		$this->callPresenterAction($this->presenter, 'product', ['product' => 1]);

		$this->validateProduct($this->presenter->getProduct());
	}


	public function testAction()
	{
		$this->callPresenterAction($this->presenter, 'edit', ['product' => 1]);

		$this->validateProduct($this->presenter->getProduct());
	}


	/**
	 * @param object $product
	 */
	private function validateProduct($product)
	{
		$this->assertInstanceOf(Product::class, $product);
		/** @var Product $product */
		$this->assertSame(1, $product->getId());
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
	 * @return IResponse
	 */
	private function callPresenterAction(Presenter $presenter, $action, $args = [])
	{
		$args['action'] = $action;
		$request = new Request($presenter->getName(), 'GET', $args);
		return $presenter->run($request);
	}

}
