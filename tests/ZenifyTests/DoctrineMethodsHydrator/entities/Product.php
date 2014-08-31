<?php

namespace ZenifyTests\DoctrineMethodsHydrator\Entities;

use Doctrine\ORM\Mapping as ORM;
use Nette;


/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 *
 * @method  int     getId()
 * @method  string  getName()
 * @method  Product setName()
 */
class Product extends Nette\Object
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	public $id;

	/**
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	protected $name;

}
