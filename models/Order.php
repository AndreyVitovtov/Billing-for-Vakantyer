<?php

namespace Model;

use DB\Database;
use Exception;
use PDOException;
use stdClass;

class Order extends Log
{
    /**
     * @var mixed
     */
    private $response;
    private $db;
    public $userId;
    public $orderId;
    public $paymentUrl;
    public $sessionId;
    public $transactionId;
    public $packageId;
    public $success;
    public $complete;
    public $status;
    public $hash;
    public $added;

    public function __construct($userId, $response = null)
    {
        parent::__construct(get_class());
        $this->userId = $userId;
        if (!empty($response)) $this->response = $response;
        $this->db = Database::instance()->getDbh();
    }

    /**
     * @throws Exception
     */
    public function add($hash, $packageId)
    {
        if (!empty($this->response)) {
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO `orders` (`userId`, `orderId`, `paymentUrl`, `sessionId`, `transactionId`, `hash`, 
                                          `packageId`, `success`) 
                    VALUES (:userId, :orderId, :paymentUrl, :sessionId, :transactionId, :hash, :packageId, :success);
                ");
                $stmt->execute([
                    'userId' => $this->userId,
                    'orderId' => $this->response->payload->orderId,
                    'paymentUrl' => $this->response->payload->paymentUrl,
                    'sessionId' => $this->response->payload->sessionId,
                    'transactionId' => $this->response->payload->transactionId,
                    'hash' => $hash,
                    'packageId' => $packageId,
                    'success' => intval($this->response->code == '00000')
                ]);
            } catch (PDOException $ex) {
                $this->setLog('add', 'Failed to add, response: ' . json_encode($this->response) . "\n" .
                    $ex->getMessage());
                throw new Exception('Failed to add, response ' . json_encode($this->response) .
                    $ex->getMessage());
            }
        } else {
            $this->setLog('add', 'Failed to add, empty response');
            throw new Exception('Failed to add, empty response');
        }
    }

    /**
     * @throws Exception
     */
    public function updateStatus($id, $status)
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE `orders` 
            SET `status` = :status
            WHERE `id` = :id
        ");
            $stmt->execute([
                'status' => $status,
                'id' => $id
            ]);
        } catch (PDOException $ex) {
            $this->setLog('updateStatus', 'Failed to update status');
            throw new Exception('Failed to update status');
        }
    }

    /**
     * @throws Exception
     */
    public function updateComplete($id, $complete)
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE `orders` 
            SET `status` = :status
            WHERE `id` = :id
        ");
            $stmt->execute([
                'complete' => $complete,
                'id' => $id
            ]);
        } catch (PDOException $ex) {
            $this->setLog('updateComplete', 'Failed to update complete');
            throw new Exception('Failed to update complete');
        }
    }

    public function getOrderByHash($hash)
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM `orders`
            WHERE `hash` = :hash
        ");
        $stmt->execute([
            'hash' => $hash
        ]);
        return $stmt->fetchObject(Order::class, ['userId' => $this->userId]);
    }

    public function createHash(): string
    {
        return md5($this->userId . time());
    }
}