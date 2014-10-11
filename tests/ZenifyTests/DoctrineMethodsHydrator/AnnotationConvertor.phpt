<?php

/**
 * Test: Zenify\DoctrineMethodsHydrator\AnnotationConvertor.
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
use ZenifyTests\DoctrineMethodsHydrator\UI\MockAnnotationPresenter;


$container = require_once __DIR__ . '/../bootstrap.php';


class AnnotationConvertorTest extends BaseTestCase
{
	/** @var Nette\DI\Container */
	private $container;

	/** @var MockAnnotationPresenter */
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


	/**
	 * @throws \Doctrine\ORM\Mapping\MappingException
	 */
	public function testNotEntity()
	{
		$this->callPresenterAction($this->presenter, 'category', ['category' => 5]);
	}
	
	
	public function testFullAnnotation()
	{
		// render
		$this->callPresenterAction($this->presenter, 'productFullAnnotation', ['product' => 'brand-new-ruler']);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::same($this->presenter->product->getId(), 1);

		$this->presenter->product = NULL;
		Assert::null($this->presenter->product);
	}
	
	
	public function testtypeHint()
	{
		$this->callPresenterAction($this->presenter, 'productTypeHint', ['product' => 'brand-new-ruler']);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::same($this->presenter->product->getId(), 1);
	}
	
	
	public function testByName()
	{
		$this->callPresenterAction($this->presenter, 'productByName', ['product' => 'Brand new ruler']);
		Assert::type('array', $this->presenter->product);
		Assert::same($this->presenter->product[0]->getId(), 1);
		Assert::same($this->presenter->product[1]->getId(), 2);
	}
	
	
	public function testConvertParam()
	{
		$this->callPresenterAction($this->presenter, 'productConvertParam', ['slug' => 'brand-new-ruler-2', 'name' => 'Brand new ruler']);
		Assert::type('array', $this->presenter->product);
		Assert::type('string', $this->presenter->slug);
		Assert::same($this->presenter->product[0]->getId(), 1);
		Assert::same($this->presenter->product[1]->getId(), 2);
		Assert::same($this->presenter->slug, 'brand-new-ruler-2');
	}
	
	
	public function testConvertAllParams()
	{
		$this->callPresenterAction($this->presenter, 'productConvertAllParams', ['slug' => 'brand-new-ruler-2', 'name' => 'Brand new ruler']);
		Assert::type('array', $this->presenter->product);
		Assert::type('array', $this->presenter->slug);
		Assert::same($this->presenter->product[0]->getId(), 1);
		Assert::same($this->presenter->product[1]->getId(), 2);
		Assert::same($this->presenter->slug[0]->getId(), 1);
		Assert::same($this->presenter->slug[1]->getId(), 2);
	}
	
	
	public function testConvertToOne()
	{
		$this->callPresenterAction($this->presenter, 'productConvertToOne', ['slug' => 'brand-new-ruler', 'name' => 'Brand new ruler']);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->slug);
		Assert::same($this->presenter->product->getId(), 1);
		Assert::same($this->presenter->slug->getId(), 1);
	}
	
	
	public function testConvertToOneAll()
	{
		$this->callPresenterAction($this->presenter, 'productConvertToOneAll', ['slug' => 'brand-new-ruler', 'name' => 'Brand new ruler']);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->slug);
		Assert::same($this->presenter->product->getId(), 1);
		Assert::same($this->presenter->slug->getId(), 1);
	}
	
	
	public function testConvertNameToOne()
	{
		$this->callPresenterAction($this->presenter, 'productConvertNameToOne', ['slug' => 'brand-new-ruler', 'name' => 'Brand new ruler']);
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\Entities\Product', $this->presenter->product);
		Assert::type('string', $this->presenter->slug);
		Assert::same($this->presenter->product->getId(), 1);
		Assert::same($this->presenter->slug, 'brand-new-ruler');
	}
	

	public function testNoValue()
	{
		Assert::error(function() {
			$this->callPresenterAction($this->presenter, 'product');
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
		Assert::type('ZenifyTests\DoctrineMethodsHydrator\UI\MockControl', $mockControl);

		$convertor = $this->invokeMethod($mockControl, 'getConvertor');
		Assert::type('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor', $convertor);
	}


	private function prepareDbData()
	{
		/** @var Connection $connection */
		$connection = $this->container->getByType('Doctrine\DBAL\Connection');
		$connection->query('CREATE TABLE product (id INTEGER NOT NULL, name string, slug string, PRIMARY KEY(id))');

		$product = new Product;
		$product->setName('Brand new ruler');
		$product->setSlug('brand-new-ruler');

		$product2 = new Product;
		$product2->setName('Brand new ruler');
		$product2->setSlug('brand-new-rule-2');

		/** @var EntityManager $em */
		$em = $this->container->getByType('Doctrine\ORM\EntityManager');
		$em->persist($product);
		$em->persist($product2);
		$em->flush();
	}


	private function buildPresenter()
	{
		$this->presenter = new MockAnnotationPresenter;
		$this->container->callMethod(array($this->presenter, 'injectPrimary'));
	}

}


\run(new AnnotationConvertorTest($container));
