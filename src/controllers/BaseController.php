<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-13
 * Time: 21:51
 */

namespace Controllers;

use Psr\Container\ContainerInterface;

class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}