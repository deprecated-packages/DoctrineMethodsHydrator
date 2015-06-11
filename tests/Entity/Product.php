<?php

namespace Zenify\DoctrineMethodsHydrator\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	private $name;


	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}
