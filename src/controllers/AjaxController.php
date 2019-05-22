<?php


namespace Controllers;


use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class AjaxController extends BaseController
{
    protected $view;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->view = $container->get('view');
    }

    public function getLocation(Request $request, Response $response)
    {
        $locationModel = $this->container->get('LocationModel');
        $data = $request->getQueryParams()['term'];
        $locations = $locationModel->getLocations('%' . $data . '%')->fetchAll();

        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $data = [
            $nameKey => $name,
            $valueKey => $value,
        ];

        foreach ($locations as $location){
            $country = empty($location['country']) ? 'Czech Republic' : ucfirst($location['country']);
            $city = ucfirst($location['city']);
            $streetName = ucfirst($location['street_name']);
            $name = ucfirst($location['name']);
            $data['location'][] = ['value' => "{$country} {$city} {$streetName} {$name}"];
        }

        return $response->withJson($data, 200);
    }
}