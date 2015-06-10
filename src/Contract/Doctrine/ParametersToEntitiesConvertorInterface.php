<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\Contract\Doctrine;


interface ParametersToEntitiesConvertorInterface
{

	/**
	 * @return mixed[]
	 */
	function process(array $methodParameters, array $args);

}
