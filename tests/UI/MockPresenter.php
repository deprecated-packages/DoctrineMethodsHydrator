<?php

namespace Zenify\DoctrineMethodsHydrator\Tests\UI;

use Nette;
use Zenify\DoctrineMethodsHydrator\Tests\Entities\Category;
use Zenify\DoctrineMethodsHydrator\Tests\Entities\Product;
use Zenify\DoctrineMethodsHydrator\MethodsHydrator;


class MockPresenter extends Nette\Application\UI\Presenter
{

	/**
	 * @var Product
	 */
	public $product;

	/**
	 * @var Category
	 */
	public $category;

	/**
	 * @inject
	 * @var MethodsHydrator
	 */
	public $methodsHydrator;

	/**
	 * @inject
	 * @var MockControlFactory
	 */
	public $mockControlFactory;


	public function renderProduct(Product $product)
	{
		$this->product = $product;
	}


	public function renderProductOptional(Product $product = NULL)
	{
		$this->product = $product;
	}


	public function actionEdit(Product $product)
	{
		$this->product = $product;
	}


	public function handleDelete(Product $product)
	{
		$this->product = $product;
	}


	public function renderCategory(Category $category)
	{
		$this->category = $category;
	}


	/**
	 * So we don't need templates for presenter
	 * @throws Nette\Application\AbortException
	 */
	public function sendTemplate()
	{
		$this->terminate();
	}


	/**
	 * @return MockControl
	 */
	protected function createComponentMockControl()
	{
		return $this->mockControlFactory->create();
	}


	/**
	 * @param string $method
	 * @param array $parameters
	 * @return bool
	 */
	protected function tryCall($method, array $parameters)
	{
		return $this->methodsHydrator->hydrate($method, $parameters, $this);
	}

}
