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
		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);
	}

}
