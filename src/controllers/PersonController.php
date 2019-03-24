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
    protected $personArr = [];

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function home(Request $request, Response $response, $args)
    {
        $person = $this->container->get('PersonModel');
        $args['persons'] = [];

        $db = $this->container->get('db');
        $stmt = $db->query("SELECT * FROM person ORDER BY first_name");
        $persons = $stmt->fetchAll();
        foreach ($persons as $person) {
            $args['persons'] += [
                $person['id_person'] => ['name' => $person['first_name'], 'id' => $person['id_person']]
            ];
        }
        return $this->container->get('view')->render($response, 'person-list.latte', $args);
    }

    public function addPerson(Request $request, Response $response, $args)
    {
        if ($request->isPost()) {
            $this->personArr[''] = ' ';
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