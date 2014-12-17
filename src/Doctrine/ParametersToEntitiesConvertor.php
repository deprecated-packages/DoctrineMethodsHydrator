<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\Doctrine;

use Doctrine;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\BadRequestException;


class ParametersToEntitiesConvertor
{

	/**
	 * @var EntityManager
	 */
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
				if ($this->isEntity($className) && $args[$i] !== NULL && $args[$i] !== FALSE) {
					$args[$i] = $this->findById($className, $args[$i]);
				}
			}
		}

		return $args;
	}


	/**
	 * @param string $entityName
	 * @param int $id
	 * @return object|NULL
	 * @throws BadRequestException
	 */
	private function findById($entityName, $id)
	{
		$entity = $this->entityManager->find($entityName, $id);

		if ($entity === NULL) {
			throw new BadRequestException('Entity "' . $entityName . '" with id = "' . $id . '" was not found.');
		}

		return $entity;
	}


	/**
	 * @param string
	 * @return bool
	 */
	private function isEntity($className)
	{
		try {
			$this->entityManager->getClassMetadata($className);
			return TRUE;

		} catch (Doctrine\Common\Persistence\Mapping\MappingException $e) {
			return FALSE;
		}
	}

}
