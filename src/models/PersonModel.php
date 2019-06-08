<?php

namespace Models;

use Exception;
use Psr\Container\ContainerInterface;

class PersonModel extends BaseModel
{
    protected $person;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function getPersons($order = 'last_name')
    {
        $query = "SELECT id_person, nickname, first_name, last_name, AGE(birth_day), height, gender, id_location FROM person";
        return $this->handleQuery($query);
    }

    public function getNotParticipatePersons($idMeeting)
    {
        $query = "SELECT DISTINCT person.id_person, nickname, first_name, last_name, gender FROM person
    inner join person_meeting pm on pm.id_person = person.id_person WHERE id_meeting <> :idm";
        $bind = [
            ':idm' => $idMeeting
        ];
        return $this->handleQuery($query, $bind);
    }

    /**
     * @param $field
     * @param null $id
     *
     * @return mixed
     * @throws Exception
     */
    public function getUserField($field, $id = null)
    {
        if (empty($this->person)) {
            if (is_null($id)) {
                throw new Exception('\'id\' not specified');
            }
            $this->getPersonById($id);
        }
        return $this->person[$field];
    }

    public function getPersonById($id, $callback = null)
    {
        if (empty($this->person)) {
            $query = "SELECT id_person, nickname, first_name, last_name, birth_day, height, gender, id_location FROM person WHERE id_person = :id ";
            $bindVals = [':id' => $id];
            $stmt = $this->handleQuery($query, $bindVals, $callback);
            $this->person = $stmt->fetchAll()[0];
        }
        return $this->person;
    }

    /**
     * @param null $id
     *
     * @return mixed
     * @throws Exception
     */
    public function getFullName($id = null)
    {
        if (empty($this->person)) {
            if (is_null($id)) {
                throw new Exception('\'id\' not specified');
            }
            $this->getPersonById($id);
        }
        return $this->person['last_name'] . $this->person['first_name'];
    }

    public function getLocation($id = null)
    {
        if (empty($this->person)) {
            if (is_null($id)) {
                throw new Exception('\'id\' not specified');
            }
            $this->getPersonById($id);
        }
        return $this->person['id_location'];
    }

    public function getBirthDay($id = null)
    {
        if (empty($this->person)) {
            if (is_null($id)) {
                throw new Exception('\'id\' not specified');
            }
            $this->getPersonById($id);
        }
        return $this->person['birth_day'];
    }

    public function editPerson($data, $callback = null)
    {
        $query = "UPDATE person 
SET nickname = :nn, first_name = :fn, last_name = :ln, birth_day = :bd, height = :hh, gender =:g, id_location = :idl 
WHERE id_person = :id";
        $values = [
            ':id' => $data['id_person'],
            ':nn' => $data['nickname'],
            ':fn' => $data['first_name'],
            ':ln' => $data['last_name'],
            ':bd' => empty($data['birth_day']) ? null : $data['birth_day'],
            ':hh' => empty($data['height']) ? null : $data['height'],
            ':g' => empty($data['gender']) ? null : $data['gender'],
            ':idl' => empty($data['id_location']) ? null : $data['id_location'],
        ];
        $this->handleQuery($query, $values, $callback);
    }

    public function newPerson($data, $callback = null)
    {
        $query = "INSERT INTO person (birth_day, gender, first_name, last_name, height, nickname, id_location) VALUES (:bd, :g, :fn, :ln, :hg, :nn, :idl)";
        $values = [
            ':bd' => empty($data['birth_day']) ? null : $data['birth_day'],
            ':fn' => $data['first_name'],
            ':ln' => $data['last_name'],
            ':g' => isset($data['gender']) ? $data['gender'] : null,
            ':hg' => empty($data['height']) ? null : $data['height'],
            ':nn' => $data['nickname'],
            ':idl' => $data['id_location']
        ];
        $this->handleQuery($query, $values, $callback);
    }

    public function deletePerson($id, $callback = null)
    {
        $query = "DELETE FROM person WHERE id_person = :id";
        $values = [':id' => $id];
        $this->handleQuery($query, $values, $callback);
    }

    function getFullInformation($id, $callback = null)
    {
        $query = "SELECT p.id_person, latitude, longitude, p.nickname, p.first_name, p.last_name, contact, city, p.gender, p.height, p.birth_day, l.country, l.street_number, l.street_name, l.name, l.zip, ct.name as contact_type
from person p
    left join contact c on p.id_person = c.id_person
    left join contact_type ct on c.id_contact_type = ct.id_contact_type
    left join location l on p.id_location = l.id_location
where p.id_person = :id";

        $values = [':id' => $id];
        return $this->handleQuery($query, $values, $callback);
    }

    public function getAllPersonMeetings($id, $callback = null)
    {
        $query = "SELECT m.id_meeting, start, description, city, name, country FROM meeting m
    INNER JOIN (SELECT id_meeting FROM person_meeting WHERE id_person = :id) idm on idm.id_meeting = m.id_meeting
    LEFT JOIN location l on m.id_location = l.id_location";

        $values = [':id' => $id];
        return $this->handleQuery($query, $values, $callback);
    }
}