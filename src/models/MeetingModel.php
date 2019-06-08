<?php


namespace Models;


use Psr\Container\ContainerInterface;

class MeetingModel extends BaseModel
{
    protected $meeting;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function getMeetings()
    {
        $query = "SELECT m.id_meeting, m.start, l.street_name, m.description, m.duration, l.country, l.city, l.name FROM meeting as m
    LEFT JOIN location l on m.id_location = l.id_location ORDER BY m.start DESC";
        $stmt = $this->handleQuery($query);
        return $stmt->fetchAll();
    }

    public function getMeetingById($id)
    {
        if (empty($this->meeting)) {
            $query = "SELECT * FROM meeting as m 
    LEFT JOIN location l on m.id_location = l.id_location WHERE id_meeting = :id ";
            $bindVals = [':id' => $id];
            $stmt = $this->handleQuery($query, $bindVals);
            $this->meeting = $stmt->fetchAll()[0];
        }
        return $this->meeting;
    }

    public function newMeeting($data, $callback = null)
    {
        $query = "INSERT INTO meeting (start, description, duration, id_location) VALUES (:st, :dsc, :dur, :idl)";
        $values = [
            ':st' => $data['start'],
            ':dsc' => $data['description'],
            ':dur' => $data['duration'],
            ':idl' => $data['id_location'],
        ];
        $this->handleQuery($query, $values, $callback);
    }

    public function getAllPersonsOnMeeting($id)
    {
        $query = "SELECT p.id_person, nickname, first_name, last_name, gender FROM person p
    INNER JOIN (SELECT id_person FROM person_meeting WHERE id_meeting = :id) a on a.id_person = p.id_person";
        $bindVals = [':id' => $id];
        return $this->handleQuery($query, $bindVals);
    }

    public function addPersonToMeeting($idMeeting, $idPerson, $callback = null)
    {
        $query = "INSERT INTO person_meeting (id_person, id_meeting)  VALUES (:idp, :idm)";
        $bindVals = [
            ':idm' => $idMeeting,
            ':idp' => $idPerson
        ];
        $this->handleQuery($query, $bindVals, $callback);
    }
}