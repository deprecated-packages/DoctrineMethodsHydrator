<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\DI;

use Nette;


class Extension extends Nette\DI\CompilerExtension
{


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('convetor'))
			->setClass('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor');
	}

}
