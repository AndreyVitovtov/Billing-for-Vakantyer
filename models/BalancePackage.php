<?php

namespace Model;

use DB\Database;
use Exception;
use PDOException;

class BalancePackage extends Log
{
    public $id;
    public $userId;
    public $numberVacancies;
    public $usedVacancies;
    public $price;
    public $packageId;
    public $term;

    private $db;

    public function __construct($userId, $numberVacancies = null, $usedVacancies = null, $price = null,
                                $packageId = null, $term = null)
    {
        $this->userId = $userId;
        if ($numberVacancies !== null) $this->numberVacancies = $numberVacancies;
        if ($usedVacancies !== null) $this->usedVacancies = $usedVacancies;
        if ($price !== null) $this->price = $price;
        if ($packageId !== null) $this->packageId = $packageId;
        if ($term !== null) $this->term = $term;
        parent::__construct(get_class());
        $this->db = Database::instance()->getDbh();
    }

    /**
     * @throws Exception
     */
    public function add()
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO `balance_package` (`userId`, `numberVacancies`, `usedVacancies`, `price`, `packageId`, `term`) 
                VALUES (:userId, :numberVacancies, :usedVacancies, :price, :packageId, :term)
            ");
            $stmt->execute([
                'userId' => $this->userId,
                'numberVacancies' => $this->numberVacancies,
                'usedVacancies' => $this->usedVacancies,
                'price' => $this->price,
                'packageId' => $this->packageId,
                'term' => ($this->term ?? date('Y-m-d H:i:s', strtotime('+ 100 YEARS', time())))
            ]);
            return $this->get($this->db->lastInsertId());
        } catch (PDOException $ex) {
            $this->setLog('add', $ex->getMessage());
            throw new Exception('Failed to add balance package: ' . $this->userId . ', sum: ' . $this->packageId);
        }
    }

    public function get($last = false)
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM `balance_package`
            WHERE `userId` = :userId
            AND `numberVacancies` > `usedVacancies`
            AND `term` >= :date
            AND `numberVacancies` > 0
            ORDER BY `added` " . ($last ? 'DESC' : 'ASC') . "
            LIMIT 1
        ");
        $stmt->execute([
            'userId' => $this->userId,
            'date' => date('Y-m-d H:i:s')
        ]);
        return $stmt->fetchObject(BalancePackage::class, [$this->userId]);
    }

    /**
     * @throws Exception
     */
    public function usedVacancy($number): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE `balance_package` 
                SET `usedVacancies` = `usedVacancies` + :number,
                    `updated` = NOW()
                WHERE `id` = :id
            ");
            $stmt->execute([
                'number' => $number,
                'id' => $this->id
            ]);
            return true;
        } catch (PDOException $ex) {
            $this->setLog('used vacancy', $ex->getMessage());
            throw new Exception('Failed to update balance package: ' . $this->userId . ', id: ' . $this->id);
        }
    }
}