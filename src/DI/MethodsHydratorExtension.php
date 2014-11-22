<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\DI;

use Nette\DI\CompilerExtension;


class MethodsHydratorExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('hydrator'))
			->setClass('Zenify\DoctrineMethodsHydrator\MethodsHydrator');

		$builder->addDefinition($this->prefix('convertor'))
			->setClass('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor');
	}

}
