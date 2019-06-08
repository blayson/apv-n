<?php

namespace Controllers;

use Controllers\Exceptions\DuplicateException;
use DateTime;
use Exception;
use Models\MeetingModel;
use Models\PersonModel;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class MeetingController extends BaseController
{
    /* @var $meeting MeetingModel */
    protected $meeting;
    protected $view;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->meeting = $container->get('MeetingModel');
        $this->view = $container->get('view');
    }

    public function home(Request $request, Response $response, $args)
    {
        $meetings = $this->meeting->getMeetings();
        $dtNow = new DateTime();
        try {
            foreach ($meetings as $key => $meeting) {
                $dt = new DateTime($meeting['start']);
                if ($dt < $dtNow) {
                    $meetings[$key]['relevant'] = false;
                } else {
                    $meetings[$key]['relevant'] = true;
                }
                $meetings[$key]['date']['date'] = $dt->format('Y-m-d');;
                $meetings[$key]['date']['time'] = $dt->format('H:i');;
                $meetings[$key]['date']['timezone'] = $dt->getTimezone()->getName();
            }
        } catch (Exception $e) {
        }

        $tplVars['meetings'] = $meetings;
        return $this->view->render($response, 'meetings-list.latte', $tplVars);
    }

    public function detail(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $meetingInfo = $this->meeting->getMeetingById($id);

        $tplVars['meetingInfo'] = $meetingInfo;
        $tplVars['maps_key'] = $this->container->get('settings')['api_keys']['gmaps'];
        $tplVars['persons'] = $this->meeting->getAllPersonsOnMeeting($id)->fetchAll();
        return $this->view->render($response, 'meeting-detail.latte', $tplVars);
    }

    public function newMeeting(Request $request, Response $response, $args)
    {
        $tplVars['form'] = [
            'start' => '',
            'duration' => '',
            'description' => '',
            'location' => '',
            'id_location' => ''
        ];

        if ($request->isPost()) {
            $data = $request->getParsedBody();
            $sqlParams = [
                'start' => $data['start'],
                'description' => $data['description'],
                'duration' => $data['duration'],
                'id_location' => $data['id_location']
            ];
            try {
                $this->meeting->newMeeting($sqlParams, function (Exception $e) {
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
                return $this->view->render($response, 'new-meeting.latte', $mergedTplVars);
            }
            return $response->withRedirect($this->container->get('router')->pathFor('newMeeting'), 301);
        }

        $mergedTplVars = array_merge($tplVars, $this->getCsrfValues($request));
        return $this->view->render($response, 'new-meeting.latte', $mergedTplVars);
    }

    public function editMeeting(Request $request, Response $response, $args)
    {

    }

    public function delete(Request $request, Response $response, $args)
    {

    }

    public function addPersonToMeeting(Request $request, Response $response, $args)
    {
        /* @var $personModel PersonModel */
        $personModel = $this->container->get('PersonModel');
        $idMeeting = $request->getAttribute('idMeeting');
        $persons = $personModel->getNotParticipatePersons($idMeeting)->fetchAll();
        $tplVars['persons'] = $persons;
        $tplVars['id_meeting'] = $idMeeting;

        if ($request->isPost()) {
            $data = $request->getParsedBody();
            try {
                $this->meeting->addPersonToMeeting($data['id_meeting'], $data['id_person'], function (Exception $e) {
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
                return $this->view->render($response, 'add-person.latte', $mergedTplVars);
            }

            return $response->withRedirect($this->container->get('router')->pathFor('addPersonToMeeting', ['idMeeting' => $data['id_meeting']]), 301);
        }
        $mergedTplVars = array_merge($tplVars, $this->getCsrfValues($request));
        return $this->view->render($response, 'add-person.latte', $mergedTplVars);
    }
}