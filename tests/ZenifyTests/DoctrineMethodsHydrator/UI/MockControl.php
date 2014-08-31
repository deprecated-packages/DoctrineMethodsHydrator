<?php

namespace ZenifyTests\DoctrineMethodsHydrator\UI;

use Nette;
use Zenify\DoctrineMethodsHydrator\Application\TTryCall;


class MockControl extends Nette\Application\UI\Control
{
	use TTryCall;

}
