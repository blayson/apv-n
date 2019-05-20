<?php

namespace Models;

use Controllers\Exceptions\DuplicateException;
use Exception;
use PDOStatement;
use Psr\Container\ContainerInterface;

class BaseModel
{
    protected $db;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = $this->container->get('db');
    }

    protected function handleQuery($query, $bindVals = null, $callback = null)
    {
        /* @var $stmt PDOStatement */
        try {
            $stmt = $this->db->prepare($query);
            if (!empty($bindVals)) {
                foreach ($bindVals as $key => $val) {
                    $stmt->bindValue($key, $val);
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            $this->container->get('logger')->error($e);
            if (is_callable($callback)) {
                $callback($e);
            } else {
                die($e->getMessage());
            }
        }
        return $stmt;
    }

    protected function handleQuery2($query, $bindVals = null, $callback = null)
    {
        /* @var $stmt PDOStatement */
        $stmt = $this->db->prepare($query);
        if (!empty($bindVals)) {
            foreach ($bindVals as $key => $val) {
                $stmt->bindValue($key, $val);
            }
        }
        $stmt->execute();

        return $stmt;
    }
}