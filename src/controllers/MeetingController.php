<?php
/**
 * Created by PhpStorm.
 * User: andriibut
 * Date: 2019-03-25
 * Time: 12:56
 */

namespace Controllers;

use Models\MeetingModel;
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
        $tplVars['meetings'] = $this->meeting->getMeetings();
        return $this->view->render($response, 'meetings-list.latte', $tplVars);
    }

    public function detail(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $meetingInfo = $this->meeting->getMeetingById($id);

        $tplVars['meetingInfo'] = $meetingInfo;
        return $this->view->render($response, 'meeting-detail.latte', $tplVars);
    }

    public function newMeeting(Request $request, Response $response, $args)
    {
        if ($request->isPost()) {
            $data = $request->getParsedBody();
//            array_key_exists('location');
            $this->meeting->newMeeting($data);
            return $response->withRedirect($this->container->get('router')->pathFor('newMeeting'), 301);
        }
        // CSRF token name and value
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $tplVars['nameKey'] = $nameKey;
        $tplVars['valueKey'] = $valueKey;
        $tplVars['name'] = $name;
        $tplVars['value'] = $value;
        return $this->view->render($response, 'new-meeting.latte', $tplVars);
    }
}