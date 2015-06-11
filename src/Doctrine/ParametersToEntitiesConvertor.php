<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\Doctrine;

use Doctrine;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\BadRequestException;
use Zenify\DoctrineMethodsHydrator\Contract\Doctrine\ParametersToEntitiesConvertorInterface;


class ParametersToEntitiesConvertor implements ParametersToEntitiesConvertorInterface
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;


	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * {@inheritdoc}
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
	 * @return object
	 * @throws BadRequestException
	 */
	private function findById($entityName, $id)
	{
		$entity = $this->entityManager->find($entityName, $id);
		if ($entity === NULL) {
			throw new BadRequestException(
				sprintf('Entity "%s" with id "%s" was not found.', $entityName, $id)
			);
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

		} catch (MappingException $e) {
			return FALSE;
		}
	}

}
