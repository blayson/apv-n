<?php

namespace Controllers;

use Controllers\Exceptions\DuplicateException;
use Exception;
use Models\PersonModel;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class PersonController extends BaseController
{
    /* @var $person PersonModel */
    protected $person;
    protected $view;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->person = $container->get('PersonModel');
        $this->view = $container->get('view');
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $persons = $this->person->getPersons()->fetchAll();
        foreach ($persons as $key => $person) {
            $persons[$key]['age'] = explode(' ', $person['age'])[0];
        }
        $tplVars['persons'] = $persons;
        return $this->view->render($response, 'persons-list.latte', $tplVars);
    }

    public function newPerson(Request $request, Response $response, $args)
    {
        $tplVars['form'] = [
            'first_name' => '',
            'last_name' => '',
            'gender' => '',
            'height' => 180,
            'nickname' => '',
            'birth_day' => '',
        ];

        if ($request->isPost()) {
            $data = $request->getParsedBody();
            $sqlData = [
                'birth_day' => $data['birth_day'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'height' => $data['height'],
                'nickname' => $data['nickname'],
                'id_location' => empty($data['id_location']) ? null : $data['id_location']
            ];
            try {
                $this->person->newPerson($sqlData, function (Exception $e) {
                    if ($e->getCode() == 23505) {
                        throw new DuplicateException('Duplicate values');
                    } else {
                        die($e->getMessage());
                    }
                });
            } catch (DuplicateException $e) {
                $tplVars['error'] = 'Duplicate values';
                $tplVars['form'] = $data;
                $mergedTplVars = array_merge($tplVars, $this->getCsrfValues($request));
                return $this->view->render($response, 'new-person.latte', $mergedTplVars);
            }
            return $response->withRedirect($this->container->get('router')->pathFor('newPerson'), 301);
        }

        $mergedTplVars = array_merge($tplVars, $this->getCsrfValues($request));
        return $this->view->render($response, 'new-person.latte', $mergedTplVars);
    }

    public function detail(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $personInfo = $this->person->getFullInformation($id)->fetchAll();
        $tplVars['maps_key'] = $this->container->get('settings')['api_keys']['gmaps'];
        $tplVars['personInfo'] = $personInfo[0];
        $tplVars['meetings'] = $this->person->getAllPersonMeetings($id)->fetchAll();
        return $this->view->render($response, 'person-detail.latte', $tplVars);
    }

    public function edit(Request $request, Response $response, $args)
    {
        if ($request->isPost()) {
            $data = $request->getParsedBody();
            $this->person->editPerson($data);
            return $response->withRedirect($this->container->get('router')->pathFor('personsList'), 301);
        }

        $id = $args['id'];
        $person = $this->person->getPersonById($id);

        $tplVars['form'] = [
            'fn' => $person['first_name'],
            'ln' => $person['last_name'],
            'nn' => $person['nickname'],
            'h' => $person['height'],
            'g' => $person['gender'],
            'bd' => $person['birth_day'],
            'id' => $person['id_person'],
            'idl' => $person['id_location'],
        ];
        $mergedTplVars = array_merge($tplVars, $this->getCsrfValues($request));
        return $this->view->render($response, 'edit-person.latte', $mergedTplVars);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = $request->getQueryParams()['id_person'];
        $this->person->deletePerson($id);
        return $response->withRedirect($this->container->get('router')->pathFor('personsList'), 301);
    }
}