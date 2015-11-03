# Doctrine Methods Hydrator

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineMethodsHydrator.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineMethodsHydrator)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineMethodsHydrator.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineMethodsHydrator)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineMethodsHydrator.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineMethodsHydrator)
[![Downloads](https://img.shields.io/packagist/dt/zenify/doctrine-methods-hydrator.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-methods-hydrator)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-methods-hydrator.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-methods-hydrator)


## Install

```sh
$ composer require zenify/doctrine-methods-hydrator
```

Register the extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineMethodsHydrator\DI\MethodsHydratorExtension
	
	# Kdyby\Doctrine or another Doctrine to Nette implementation
```

The goal of this extension is to enhance native `tryCall` method of `Control` to hydrate parameters of called methods.
All `render*`, `action*` and `handle*` methods are hydrated, if entity class typehint is present if args definition.


## Usage

Use in presenter looks like this:

```php
class Presenter extends Nette\Application\UI\Presenter
{

	/**
	 * @inject
   	 * @var Zenify\DoctrineMethodsHydrator\Contract\MethodsHydratorInterface
   	 */
   	public $methodsHydrator;


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
```

For `Control`, you can use constructor or `@inject` with help of [DecoratorExtension](http://api.nette.org/2.3/Nette.DI.Extensions.DecoratorExtension.html).


### Use Case

In template

```html
<a n:href="Product:detail, product => $product->getId()">Product detail</a>
```

In presenter

```php
class SomePresenter extends Presenter
{

	public function actionDetail(App\Entities\Product $product)
	{
		dump($product); // "App\Entities\Product" object
	}

}
```
