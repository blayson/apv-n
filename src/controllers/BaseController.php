<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-13
 * Time: 21:51
 */

namespace Controllers;

use Psr\Container\ContainerInterface;
use Slim\Csrf\Guard;

class BaseController
{
    protected $container;
    protected $csrf;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->csrf = new Guard();
    }
}