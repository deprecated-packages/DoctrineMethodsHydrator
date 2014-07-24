<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator;

use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Application\BadRequestException;
use Nette\Reflection\ClassType;


class ParametersToEntitiesConvertor extends Nette\Object
{
	/** @var EntityManager */
	private $entityManager;


	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * @return mixed[]
	 */
	public function process(array $methodParameters, array $args)
	{
		foreach ($methodParameters as $i => $parameter) {
			if ($className = $parameter->className) {
				$rc = ClassType::from($className);
				if ($rc->is('Kdyby\Doctrine\Entities\BaseEntity') && ($args[$i] !== NULL || $args[$i] !== FALSE)) {
					$args[$i] = $this->findById($className, $args[$i]);
				}
			}
		}

		return $args;
	}


	/**
	 * @param string
	 * @param int
	 * @return object|NULL
	 * @throws  BadRequestException
	 */
	private function findById($entityName, $id)
	{
		$entity = $this->entityManager->find($entityName, $id);
		if ($entity === NULL) {
			throw new BadRequestException("Id '$id' for entity '$entityName' was not found.");
		}

		return $entity;
	}

}
