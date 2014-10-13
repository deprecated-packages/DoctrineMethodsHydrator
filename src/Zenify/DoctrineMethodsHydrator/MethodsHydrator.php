<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator;

use Nette;
use Nette\Application\UI\Control;


class MethodsHydrator extends Nette\Object
{

	/**
	 * @var ParametersToEntitiesConvertor
	 */
	private $parametersToEntitiesConvertor;


	public function __construct(ParametersToEntitiesConvertor $parametersToEntitiesConvertor)
	{
		$this->parametersToEntitiesConvertor = $parametersToEntitiesConvertor;
	}


	/**
	 * @param string $method
	 * @param array $parameters
	 * @param Control $control
	 * @return bool
	 */
	public function hydrate($method, array $parameters, Control $control)
	{
		$rc = $control->getReflection();
		if ( ! $rc->hasMethod($method)) {
			return FALSE;
		}


		$rm = $rc->getMethod($method);
		if ( ! $rm->isPublic() || $rm->isAbstract() || $rm->isStatic()) {
			return FALSE;
		}

		$control->checkRequirements($rm);

		$args = $rc->combineArgs($rm, $parameters);
		if (preg_match('~^(action|render|handle).+~', $method)) {
			$args = $this->parametersToEntitiesConvertor->process($rm->parameters, $args);
		}
		$rm->invokeArgs($control, $args);

		return TRUE;
	}

}
