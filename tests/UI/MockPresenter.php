<?php

namespace Zenify\DoctrineMethodsHydrator\Tests\UI;

use Nette\Application\UI\Presenter;
use Zenify\DoctrineMethodsHydrator\Contract\MethodsHydratorInterface;
use Zenify\DoctrineMethodsHydrator\Tests\Entity\Category;
use Zenify\DoctrineMethodsHydrator\Tests\Entity\Product;


class MockPresenter extends Presenter
{

	/**
	 * @var Product
	 */
	private $product;

	/**
	 * @var Category
	 */
	public $category;

	/**
	 * @inject
	 * @var MethodsHydratorInterface
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
	 * @return Product
	 */
	public function getProduct()
	{
		return $this->product;
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
