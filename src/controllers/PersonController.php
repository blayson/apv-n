<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-13
 * Time: 22:08
 */

namespace Controllers;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class PersonController extends BaseController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function home(Request $request, Response $response, $args)
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

    public function addPerson(Request $request, Response $response, $args)
    {
        if ($request->isPost()) {
            $args['id'] = '42';
            return $this->container->get('view')->render($response, 'done.latte', $args);
        }

        // CSRF token name and value
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $args['nameKey'] = $nameKey;
        $args['valueKey'] = $valueKey;
        $args['name'] = $name;
        $args['value'] = $value;

        return $this->container->get('view')->render($response, 'add-person.latte', $args);
    }

    public function detail(Request $request, Response $response, $args)
    {
        $person = $this->container->get('PersonModel');
        $args['personName'] = $person->getName();
        return $this->container->get('view')->render($response, 'person.latte', $args);
    }
}