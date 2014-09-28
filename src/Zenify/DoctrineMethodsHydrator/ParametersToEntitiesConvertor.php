<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator;

use Doctrine;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Application\BadRequestException;


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
	public function process(array $methodParameters, array $args, $rm)
	{
		if($rm->hasAnnotation('hydratorMethod') && $rm->hasAnnotation('hydratorDao'))
		{
			return $this->useCustomMethod($methodParameters, $args, $rm);
		} else
		{
			return $this->useFindById($methodParameters, $args);
		}
	}
	
	
	/**
	 * @return mixed[]
	 */
	private function useFindById(array $methodParameters, array $args)
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
	 * @return mixed[]
	 */
	private function useCustomMethod(array $methodParameters, array $args, $rm)
	{
		$hydratatorMethod = $rm->getAnnotation('hydratorMethod');
		$hydratatorDao = $rm->getAnnotation('hydratorDao');
		foreach ($methodParameters as $i => $parameter) {
			if ($parameter->className || $rm->hasAnnotation('hydratatorEntity')) {
				$className = !is_null($parameter->className) ? 
					$parameter->className : $rm->getAnnotation('hydratatorEntity');
				if ($this->isEntity($className) && $args[$i] !== NULL && $args[$i] !== FALSE) {
					$dao = new $hydratatorDao($this->em);
					$entity = $dao->$hydratatorMethod($args[$i]);
					$args[$i] = $this->checkEntity($entity);
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
			$this->em->getClassMetadata($className);
			return TRUE;

		} catch (Doctrine\Common\Persistence\Mapping\MappingException $e) {
			return FALSE;
		}
	}
	
	
	/**
	 * 
	 * @param array $entity
	 * @return array
	 * @throws BadRequestException
	 */
	private function checkEntity(array $entity)
	{
		if (!count($entity)) {
			throw new BadRequestException('Entity "' . $className . '" with id = "' . $args[$i] . '" was not found.');
		}
		if(count($entity) === 1)
		{
			$entity = $entity[0];
		}
		return $entity;
	}

}
