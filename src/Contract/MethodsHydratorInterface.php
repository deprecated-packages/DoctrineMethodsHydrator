<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\Contract;

use Nette\Application\UI\Control;


interface MethodsHydratorInterface
{

	/**
	 * @param string $method
	 * @param array $parameters
	 * @param Control $control
	 * @return bool
	 */
	function hydrate($method, array $parameters, Control $control);

}
