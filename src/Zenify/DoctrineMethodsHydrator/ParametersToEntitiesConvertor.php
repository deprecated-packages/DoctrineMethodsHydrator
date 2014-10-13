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

	/**
	 * @var EntityManager
	 */
	private $em;

	private $args;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @return mixed[]
	 */
	public function process(array $methodParameters, array $args, $rm)
	{
		if ($rm->hasAnnotation('hydratorMethod') && $rm->hasAnnotation('hydratorDao')) {
			return $this->useCustomMethod($methodParameters, $args, $rm);

		} else {
			return $this->useFindById($methodParameters, $args);
		}
	}


	/**
	 *
	 * @param array $methodParameters
	 * @param array $args
	 * @return mixed[]
	 */
	private function useFindById(array $methodParameters, array $args)
	{
		foreach ($methodParameters as $i => $parameter) {
			$className = $parameter->className;
			if ($className && $this->isEntity($className) && $args[$i] !== NULL && $args[$i] !== FALSE) {
				$args[$i] = $this->findById($className, $args[$i]);
			}
		}

		return $args;
	}


	/**
	 *
	 * @param array $methodParameters
	 * @param array $args
	 * @param Nette\Reflection\Method $rm
	 * @return mixed[]
	 */
	private function useCustomMethod(array $methodParameters, array $args, $rm)
	{
		$hydratatorDao = $rm->getAnnotation('hydratorDao');
		$convertParams = Nette\Utils\Strings::split($rm->getAnnotation('convertParams'), '~, |,~');
		$hydratatorMethod = \Nette\Utils\Strings::matchAll($rm->getAnnotation('hydratorMethod'), '~[^$ ,()]+~')[0][0];
		$this->args = $args;

		$dao = new $hydratatorDao($this->em);

		foreach ($methodParameters as $i => $parameter) {
			if ($parameter->className || $rm->hasAnnotation('hydratatorEntity')) {
				$className = ! is_null($parameter->className) ? $parameter->className : $rm->getAnnotation('hydratatorEntity');

				if ($this->isEntity($className) && $args[$i] !== NULL && $args[$i] !== FALSE
					&& (is_null($rm->getAnnotation('convertParams')) || in_array($parameter->name, $convertParams))
				) {
					$entity = call_user_func_array(array($dao, $hydratatorMethod), $this->args);
					$args[$i] = $this->checkEntity($entity, $className, $args[$i]);
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
	 * @param string $className
	 * @param mixed $arg
	 * @return array
	 * @throws BadRequestException
	 */
	private function checkEntity(array $entity, $className, $arg)
	{
		if ( ! count($entity)) {
			throw new BadRequestException('Entity "' . $className . '" with some column = "' . $arg . '" was not found.');
		}

		if (count($entity) === 1) {
			$entity = $entity[0];
		}

		return $entity;
	}

}
