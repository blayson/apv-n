<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-26
 * Time: 17:31
 */

namespace Api;

use Slim\Http\Request;
use Slim\Http\Response;

class PersonController {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function home(Request $request, Response $response, $args)
    {
//        $person = $this->container->get('PersonModel');
//        $args['persons'] = [];

        $db = $this->container->get('db');
        $stmt = $db->query("SELECT * FROM person ORDER BY first_name");
        $stmt2 = $db->query("SELECT relation.id_person1, p1.first_name, p1.last_name,
                                    relation.id_person2, p2.first_name, p2.last_name
                            FROM relation 
                            JOIN person as p1
                            ON p1.id_person = relation.id_person1
                            JOIN person as p2 
                            ON p2.id_person = relation.id_person2
                            JOIN relation_type 
                            USING (id_relation_type)");
        $persons = $stmt->fetchAll();
        $tmp = $stmt2->fetchAll();
//        foreach ($persons as $person) {
//            $args['persons'] += [
//                $person['id_person'] => ['name' => $person['first_name'], 'id' => $person['id_person']]
//            ];
//        }
//        return $this->container->get('view')->render($response, 'person-list.latte', $args);
        return $response->withJson($tmp);
    }
}