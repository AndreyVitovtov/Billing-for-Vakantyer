<?php

namespace Model;

use DB\Database;
use Exception;
use PDOException;

class Balance extends Log
{
    public $userId;
    public $balance;
    private $db;

    /**
     * @throws Exception
     */
    public function __construct($userId, $balance = null)
    {
        parent::__construct(get_class());
        $this->userId = $userId;
        if ($balance !== null) $this->balance = $balance;
        $this->db = Database::instance()->getDbh();
    }

    /**
     * @throws Exception
     */
    public function get(): Balance
    {
        $stmt = $this->db->prepare("
            SELECT `userId`, `balance` 
            FROM `balance` 
            WHERE `userid` = :userId
        ");
        $stmt->execute([
            'userId' => $this->userId
        ]);
        $balance = $stmt->fetchObject(Balance::class, [$this->userId]);
        if (!$balance) {
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO `balance` (userId, balance) VALUES (:userId, :balance)
                ");
                $stmt->execute([
                    'userId' => $this->userId,
                    'balance' => $this->balance
                ]);
                return $this->get();
            } catch (PDOException $ex) {
                $this->setLog('add user balance', $ex->getMessage());
                throw new Exception('Failed to add user balance: ' . $this->userId . ', sum: ' . $this->balance);
            }
        }

        return $balance;
    }

    /**
     * @throws Exception
     */
    public function add($sum): Balance
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE `balance` 
                SET `balance` = `balance` + :sum, 
                    `updated` = NOW() 
                WHERE `userId` = :userId
            ");
            $stmt->execute([
                'sum' => $sum,
                'userId' => $this->userId
            ]);
            return $this->get();
        } catch (PDOException $ex) {
            $this->setLog('add', $ex->getMessage());
            throw new Exception('Failed to top up balance userId: ' . $this->userId . ', sum: ' . $sum);
        }
    }

    /**
     * @throws Exception
     */
    public function writeOff($sum): Balance
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE `balance` 
            SET `balance` = `balance` - :sum,
                `updated` = NOW()
            WHERE `userId` = :userId
        ");
            $stmt->execute([
                'sum' => $sum,
                'userId' => $this->userId
            ]);
            return $this->get();
        } catch (PDOException $ex) {
            $this->setLog('add', $ex->getMessage());
            throw new Exception('Failed to unbalance userId: ' . $this->userId . ', sum: ' . $sum);
        }
    }

    public function getFloat(): float
    {
        return floatval($this->get()->balance);
    }
}