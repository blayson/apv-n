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

    public function getLocations($pattern, $callback = null)
    {
        $pattern = strtolower($pattern);
        $query = "SELECT id_location, city, street_name, street_number, zip, country, name FROM location
WHERE lower(city) LIKE :p0 or lower(street_name) LIKE :p1 or lower(country) LIKE :p3 or lower(name) LIKE :p4";
        $bind = [
            ':p0' => $pattern,
            ':p1' => $pattern,
            ':p3' => $pattern,
            ':p4' => $pattern,
        ];
        if (preg_match('%(cz|cze|czech)%', $pattern)) {
            $query = "SELECT id_location, city, street_name, street_number, zip, country, name FROM location
WHERE lower(city) LIKE :p0 or lower(street_name) LIKE :p1 or lower(country) ISNULL or lower(name) LIKE :p4";
            $bind = [
                ':p0' => $pattern,
                ':p1' => $pattern,
                ':p4' => $pattern,
            ];
        }
        return $this->handleQuery($query, $bind, $callback);
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