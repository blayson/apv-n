<?php

namespace Controllers;

use Psr\Container\ContainerInterface;
use Slim\Csrf\Guard;
use Slim\Http\Request;

class BaseController
{
    protected $container;
    protected $csrf;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->csrf = new Guard();
    }

    protected function getCsrfValues(Request $request) {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $tplVars['nameKey'] = $nameKey;
        $tplVars['valueKey'] = $valueKey;
        $tplVars['name'] = $name;
        $tplVars['value'] = $value;
        return $tplVars;
    }
}