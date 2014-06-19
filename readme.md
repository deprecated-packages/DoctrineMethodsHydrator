# Zenify/DoctrineMethodsHydrator


## Requirements

See section `require` in [composer.json](composer.json).


## Installation

The best way to install is using [Composer](http://getcomposer.org/).

Add to your `composer.json`:

```yaml
"require": {
        "zenify/doctrine-methods-hydrator": "@dev",
}
```

```sh
$ composer update
```

Register the extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineMethodsHydrator\DI\Extension
```

Place trait to your parent presenter 

```php
class Presenter extends Nette\Application\UI\Presenter 
{
	use Zenify\DoctrineMethodsHydrator\Application\TTryCall;

}
```


## Profit

In template

```smarty

<a n:href="Product:detail, product => $product->id">Product detail</a>
```

In presenter

```php
class SomePresenter extends Presenter 
{
	
	public function actionDetail(Product $product) 
	{
		dd($product); // Product entity 
	}

}
```



