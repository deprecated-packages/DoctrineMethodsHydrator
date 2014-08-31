# Zenify/DoctrineMethodsHydrator


## Installation

The best way to install is using [Composer](http://getcomposer.org/).

```sh
$ composer require zenify/doctrine-methods-hydrator:@dev
```

Register the extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineMethodsHydrator\DI\MethodsHydratorExtension
```

Place trait to your parent presenter or control:

```php
class Presenter extends Nette\Application\UI\Presenter
{
	use Zenify\DoctrineMethodsHydrator\Application\TTryCall;

}
```


## Profit

In template

```html
<a n:href="Product:detail, product => $product->id">Product detail</a>
```

In presenter

```php
class SomePresenter extends Presenter
{

	public function actionDetail(Product $product)
	{
		dump($product); // Product entity
	}

}
```
