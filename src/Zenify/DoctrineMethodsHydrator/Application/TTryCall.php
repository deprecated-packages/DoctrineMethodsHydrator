<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\Application;

use Nette\Application\UI\Presenter;
use Zenify;


trait TTryCall
{
	/**
	 * @inject
	 * @var \Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor
	 */
	public $parametersToEntitiesConvertor;


	/**
	 * @param  string
	 * @param  array
	 * @return bool
	 */
	protected function tryCall($method, array $parameters)
	{
		/** @var Presenter $presenter */
		$presenter = $this;
		$rc = $presenter->getReflection();
		if ( ! $rc->hasMethod($method)) {
			return FALSE;
		}

		$rm = $rc->getMethod($method);
		if ( ! $rm->isPublic() || $rm->isAbstract() || $rm->isStatic()) {
			return FALSE;
		}

		$presenter->checkRequirements($rm);
		$args = $rc->combineArgs($rm, $parameters);

		if (preg_match('~^(action|render|handle).+~', $method)) {
			$args = $this->parametersToEntitiesConvertor->process($rm->parameters, $args);
		}

		$rm->invokeArgs($this, $args);

		return TRUE;
	}

}


