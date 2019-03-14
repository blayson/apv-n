<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-13
 * Time: 21:49
 */

namespace Controllers;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class MainController extends BaseController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

    }

    public function __invoke(Request $request, Response $response, $args)
    {
        return $this->container->get('view')->render($response, 'main.latte', $args);
    }
}