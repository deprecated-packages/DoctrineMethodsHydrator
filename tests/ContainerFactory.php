<?php

namespace Zenify\DoctrineMethodsHydrator\Tests;

use Nette\Configurator;
use Nette\DI\Container;


class ContainerFactory
{

	/**
	 * @return Container
	 */
	public function create()
	{
		$configurator = new Configurator;
		$configurator->setTempDirectory($this->createTempDir());
		$configurator->addConfig(__DIR__ . '/config/default.neon');
		return $configurator->createContainer();
	}


	/**
	 * @return string
	 */
	private function createTempDir()
	{
		@mkdir(__DIR__ . '/temp'); // @ - directory may exists
		@mkdir($tempDir = __DIR__ . '/temp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
		return realpath($tempDir);
	}

}
