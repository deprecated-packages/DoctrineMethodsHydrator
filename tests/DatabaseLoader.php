<?php

namespace Zenify\DoctrineMethodsHydrator\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
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
	 * @var EntityManagerInterface
	 */
	private $entityManager;


	public function __construct(Connection $connection, EntityManagerInterface $entityManager)
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

		$this->entityManager->persist(new Product('Brand new ruler'));
		$this->entityManager->flush();

		$this->isDbPrepared = TRUE;
	}

}
