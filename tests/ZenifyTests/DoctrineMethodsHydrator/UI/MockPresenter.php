<?php

namespace ZenifyTests\DoctrineMethodsHydrator\UI;

use Nette;
use Zenify\DoctrineMethodsHydrator\Application\TTryCall;
use ZenifyTests\DoctrineMethodsHydrator\Entities\Category;
use ZenifyTests\DoctrineMethodsHydrator\Entities\Product;


class MockPresenter extends Nette\Application\UI\Presenter
{
	use TTryCall;

	/** @var Product */
	public $product;

	/** @var Category */
	public $category;


	public function renderProduct(Product $product)
	{
		$this->product = $product;
	}
	
	
	public function renderProductOptional(Product $product = null)
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
		return new MockControl;
	}

}
