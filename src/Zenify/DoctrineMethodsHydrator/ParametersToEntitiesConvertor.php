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
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @return mixed[]
	 */
	public function process(array $methodParameters, array $args)
	{
		foreach ($methodParameters as $i => $parameter) {
			if ($className = $parameter->className) {
				if ($this->isEntity($className) && ($args[$i] !== NULL || $args[$i] !== FALSE)) {
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
	 * @throws BadRequestException
	 */
	private function findById($entityName, $id)
	{
		$entity = $this->em->find($entityName, $id);
		if ($entity === NULL) {
			throw new BadRequestException("Id '$id' for entity '$entityName' was not found.");
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
			$this->em->getClassMetadata($className);
			return TRUE;

		} catch (\Exception $e) {
			return FALSE;
		}
	}

}
