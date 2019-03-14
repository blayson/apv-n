<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-13
 * Time: 22:08
 */

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class PersonController extends BaseController
{
    public function __invoke(Request $request, Response $response, $args)
    {
//        $id = $args['id'];
        $person = $this->container->get('PersonModel');
        $args['personName'] = $person->getName();
        return $this->container->get('view')->render($response, 'person.latte', $args);
    }
}