<?php

namespace ZenifyTests\Dao;


class Products
{
	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;
	private $productDao;

	public function __construct(\Kdyby\Doctrine\EntityManager $em)
	{
		$this->em = $em;
		$this->productDao = $em->getDao('ZenifyTests\DoctrineMethodsHydrator\Entities\Product');
	}
	
	
	public function findBySlug($slug)
	{
		return $this->productDao->findBySlug($slug);
	}
	
	
	public function findByName($name)
	{
		return $this->productDao->findByName($name);
	}
	
	
	public function findBySlugName($name, $slug)
	{
		return $this->productDao->findBy(array('name' => $name, 'slug' => $slug));
	}
}
