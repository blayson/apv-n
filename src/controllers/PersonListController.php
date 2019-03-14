<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-12
 * Time: 18:14
 */

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class PersonListController extends BaseController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $person = $this->container->get('PersonModel');
        $args['persons'] = [
            '1' => ['name' => 'Andrii', 'id' => '1'],
            '2' => ['name' => 'Alex', 'id' => '2'],
            '3' => ['name' => 'Lena', 'id' => '3'],
            '4' => ['name' => 'Danylo', 'id' => '4'],
        ];

        return $this->container->get('view')->render($response, 'person-list.latte', $args);
    }
}