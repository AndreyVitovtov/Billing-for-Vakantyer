<?php

namespace Model;

use DB\Database;
use PDOException;

class Package extends Log
{
    public $id;
    public $price;
    public $vacancyCost;
    private $db;

    public function __construct($id = null, $price = null, $vacancyCost = null)
    {
        parent::__construct(get_class());
        if($price !== null) $this->price = $price;
        if($id !== null) $this->id = $id;
        if($vacancyCost !== null) $this->vacancyCost = $vacancyCost;
        $this->db = Database::instance()->getDbh();
    }

    public function add(): bool
    {
        try {
            $stmt = $this->db->prepare("
            INSERT INTO `package` (`price`, `vacancyCost`) VALUES (:price, :vacancyCost)
        ");
            $stmt->execute([
                'price' => $this->price,
                'vacancyCost' => $this->vacancyCost
            ]);
            return true;
        } catch (PDOException $ex) {
            $this->setLog('add', $ex->getMessage());
            return false;
        }
    }

    public function remove($id): bool
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE `package` SET `removed` = 0 WHERE `id` = :id
        ");
            $stmt->execute([
                'id' => $id
            ]);
            return true;
        } catch (PDOException $ex) {
            $this->setLog('remove', $ex->getMessage());
            return false;
        }
    }

    public function get($id): Package
    {
        $stmt = $this->db->prepare("
            SELECT `price`, `vacancyCost`, `id` FROM `package` WHERE `id` = :id AND `removed` = 0
        ");
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetchObject(Package::class);
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM `package` WHERE `removed` = 0
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}