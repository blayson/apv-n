<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-13
 * Time: 22:08
 */

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
        $tplVars['persons'] = $this->person->getPersons();
        return $this->view->render($response, 'persons-list.latte', $tplVars);
    }

    public function newPerson(Request $request, Response $response, $args)
    {
        if ($request->isGet() ) {
            $tplVars['form'] = [
                'first_name' => '',
                'last_name' => '',
                'gender' => '',
                'height' => 180,
                'nickname' => '',
                'birth_day' => '',
            ];
            // CSRF token name and value
            $mergedTplVars = array_merge($tplVars, $this->getCsrfValues($request));
            return $this->view->render($response, 'new-person.latte', $mergedTplVars);
        }

        if ($request->isPost()) {
            $data = $request->getParsedBody();
            try {
                $this->person->newPerson($data, function (Exception $e) {
                    if ($e->getCode() == 23505) {
                        throw new DuplicateException('Duplicate values');
                    } else {
//                        var_dump($e);
                        die($e->getMessage());
                    }
                });
            } catch (DuplicateException $e) {
                $tplVars['error'] = 'Duplicate values';
                $tplVars['form'] = $data;
                $mergedTplVars = array_merge($tplVars, $this->getCsrfValues($request));
                return $this->view->render($response, 'new-person.latte', $mergedTplVars);
            }
        }
        return $response->withRedirect($this->container->get('router')->pathFor('newPerson'), 301);
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

    public function detail(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $personInfo = $this->person->getFullInformation($id)->fetchAll();
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