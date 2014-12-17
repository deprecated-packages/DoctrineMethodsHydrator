<?php

namespace Zenify\DoctrineMethodsHydrator\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Zenify\DoctrineMethodsHydrator\Tests\Entities\Product;


class DatabaseLoader
{

	/**
	 * @var bool
	 */
	private $isDbPrepared = FALSE;

	/**
	 * @var Connection
	 */
	private $connection;

	/**
	 * @var EntityManager
	 */
	private $entityManager;


	public function __construct(Connection $connection, EntityManager $entityManager)
	{
		$this->connection = $connection;
		$this->entityManager = $entityManager;
	}


	public function loadProductTableWithOneItem()
	{
		if ($this->isDbPrepared) {
			return;
		}

		$this->connection->query('CREATE TABLE product (id INTEGER NOT NULL, name string, PRIMARY KEY(id))');

		$product = new Product;
		$product->setName('Brand new ruler');

		$this->entityManager->persist($product);
		$this->entityManager->flush();

		$this->isDbPrepared = TRUE;
	}

}
