<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMethodsHydrator\Application;

use Nette;
use Zenify;


/**
 * @method Nette\Application\UI\Presenter getPresenter()
 */
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
		$presenter = $this->getPresenter();
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
			$args = $this->getConvertor()->process($rm->parameters, $args, $rm);
		}

		$rm->invokeArgs($this, $args);

		return TRUE;
	}


	/**
	 * @return Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor
	 */
	private function getConvertor()
	{
		if ($this->parametersToEntitiesConvertor === NULL) {
			$this->parametersToEntitiesConvertor = $this->getPresenter()
				->getContext()
				->getByType('Zenify\DoctrineMethodsHydrator\ParametersToEntitiesConvertor');
		}

		return $this->parametersToEntitiesConvertor;
	}

}
