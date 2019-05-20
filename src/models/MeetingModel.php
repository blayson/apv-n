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
        $query = "SELECT id_meeting, start, description, duration, id_location FROM meeting";
        $stmt = $this->handleQuery($query);
        return $stmt->fetchAll();
    }

    public function getMeetingById($id)
    {
        if (empty($this->person)) {
            $query = "SELECT id_meeting, start, description, duration, id_location FROM meeting WHERE id_meeting = :id ";
            $bindVals = [':id' => $id];
            $stmt = $this->handleQuery($query, $bindVals);
            $this->meeting = $stmt->fetchAll()[0];
        }
        return $this->meeting;
    }

    public function newMeeting($data)
    {
        $query = "INSERT INTO meeting (start, description, duration, id_location) VALUES (:st, :dsc, :dur, :idl)";
        $values = [
            ':st' => $data['start'],
            ':dsc' => $data['description'],
            ':dur' => $data['duration'],
            ':idl' => $data['id_location'],
        ];
        $this->handleQuery($query, $values);
    }
}