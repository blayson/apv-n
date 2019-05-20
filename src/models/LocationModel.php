<?php


namespace Models;


use Psr\Container\ContainerInterface;

class LocationModel extends BaseModel
{

    protected $location;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function getLocations()
    {
        $query = "SELECT id_location, city, street_name, street_number, zip, country, name, latitude, longitude FROM location";
        $stmt = $this->handleQuery($query);
        return $stmt->fetchAll();
    }

    public function getLocationById($id)
    {
        if (empty($this->person)) {
            $query = "SELECT id_meeting, start, description, duration, id_location FROM meeting WHERE id_meeting = :id ";
            $bindVals = [':id' => $id];
            $stmt = $this->handleQuery($query, $bindVals);
            $this->location = $stmt->fetchAll()[0];
        }
        return $this->location;
    }
}