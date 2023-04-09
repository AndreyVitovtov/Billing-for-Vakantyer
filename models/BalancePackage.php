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
    public $typePay;
    public $orderId;
    public $status;
    private $db;

    public function __construct($userId, $numberVacancies = null, $usedVacancies = null, $price = null,
                                $packageId = null, $term = null, $typePay = null, $orderId = null, $status = 'pending')
    {
        $this->userId = $userId;
        if ($numberVacancies !== null) $this->numberVacancies = $numberVacancies;
        if ($usedVacancies !== null) $this->usedVacancies = $usedVacancies;
        if ($price !== null) $this->price = $price;
        if ($packageId !== null) $this->packageId = $packageId;
        if ($term !== null) $this->term = $term;
        if ($typePay !== null) $this->typePay = $typePay;
        if ($orderId !== null) $this->orderId = $orderId;
        if ($status !== null) $this->status = $status;
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
                INSERT INTO `balance_package` (`userId`, `numberVacancies`, `usedVacancies`, `price`, `packageId`, 
                                               `term`, `typePay`, `orderId`, `status`) 
                VALUES (:userId, :numberVacancies, :usedVacancies, :price, :packageId, :term, :typePay, :orderId, :status)
            ");
            $stmt->execute([
                'userId' => $this->userId,
                'numberVacancies' => $this->numberVacancies,
                'usedVacancies' => $this->usedVacancies,
                'price' => $this->price,
                'packageId' => $this->packageId,
                'term' => ($this->term ?? date('Y-m-d H:i:s', strtotime('+ 10 YEARS', time()))),
                'typePay' => $this->typePay,
                'orderId' => $this->orderId,
                'status' => $this->status
            ]);
            return $this->get($this->db->lastInsertId());
        } catch (PDOException $ex) {
            $this->setLog('add', $ex->getMessage());
            throw new Exception('Failed to add balance package: ' . $this->userId . ', packageId: ' .
                $this->packageId);
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

    public function getPaymentBank()
    {
        $stmt = $this->db->prepare("
            SELECT *, IF(`status` = 'pending', 0, IF(`status` = 'sent', 1, 2)) AS sort
            FROM `balance_package`
            WHERE `typePay` = 'bank'
            ORDER BY sort, `added`
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param $status string (pending | sent | paid)
     * @throws Exception
     */
    public function updateStatus($id, string $status): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE `balance_package` 
                SET `status` = :status,
                    `updated` = NOW()
                WHERE `id` = :id
            ");
            $stmt->execute([
                'status' => $status,
                'id' => $id
            ]);
            return true;
        } catch (PDOException $ex) {
            $this->setLog('update status', $ex->getMessage());
            throw new Exception('Failed to update: ' . $this->userId . ' status: ' . $status);
        }
    }
}