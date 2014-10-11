<?php

namespace ZenifyTests\DoctrineMethodsHydrator\UI;

use Nette;
use Zenify\DoctrineMethodsHydrator\Application\TTryCall;
use ZenifyTests\DoctrineMethodsHydrator\Entities\Category;
use ZenifyTests\DoctrineMethodsHydrator\Entities\Product;


class MockAnnotationPresenter extends Nette\Application\UI\Presenter
{
	use TTryCall;

	/** @var Product */
	public $product;
	
	/** @var Category */
	public $category;
	
	/** @var string */
	public $slug;
	
	
	/**
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlug
	 */
	public function renderProduct(Product $product)
	{
		$this->product = $product;
	}
	
	
	/**
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlug
	 */
	public function renderProductOptional(Product $product = NULL)
	{
		$this->product = $product;
	}
	
	
	/**
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Category
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlug
	 */
	public function renderCategory($category)
	{
		$this->category = $category;
	}
	
	
	/**
	 * 
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Product
	 * @convertParams product
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlug
	 */
	public function renderProductFullAnnotation($product)
	{
		$this->product = $product;
	}
	
	
	/**
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlug
	 */
	public function renderProductTypeHint(Product $product)
	{
		$this->product = $product;
	}
	
	
	/**
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Product
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findByName
	 */
	public function renderProductByName($product)
	{
		$this->product = $product;
	}
	
	
	/**
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Product
	 * @convertParams name
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findByName
	 */
	public function renderProductConvertParam($name, $slug)
	{
		$this->product = $name;
		$this->slug = $slug;
	}
	
	
	/**
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Product
	 * @convertParams name, slug
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findByName
	 */
	public function renderProductConvertAllParams($name, $slug)
	{
		$this->product = $name;
		$this->slug = $slug;
	}
	
	
	/**
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Product
	 * @convertParams name, slug
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlugName
	 */
	public function renderProductConvertToOne($name, $slug)
	{
		$this->product = $name;
		$this->slug = $slug;
	}
	
	
	/**
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Product
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlugName
	 */
	public function renderProductConvertToOneAll($name, $slug)
	{
		$this->product = $name;
		$this->slug = $slug;
	}
	
	
	/**
	 * @hydratatorEntity ZenifyTests\DoctrineMethodsHydrator\Entities\Product
	 * @convertParams name
	 * @hydratorDao \ZenifyTests\Dao\Products
	 * @hydratorMethod findBySlugName
	 */
	public function renderProductConvertNameToOne($name, $slug)
	{
		$this->product = $name;
		$this->slug = $slug;
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
