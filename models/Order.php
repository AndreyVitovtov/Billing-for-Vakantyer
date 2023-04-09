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
    private $userId;

    public function __construct($userId, $response = (new stdClass()))
    {
        parent::__construct(get_class());
        $this->userId = $userId;
        if (empty($response)) {
            $response = file_get_contents('php://input');
        }
        if (!empty($response)) $this->response = json_decode($response);
        $this->db = Database::instance()->getDbh();
    }

    /**
     * @throws Exception
     */
    public function add()
    {
        if (!empty($this->response)) {
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO `orders` (userId, orderId, sessionId, code) VALUES (:userId, :orderId, :sessionId, :code);
                ");
                $stmt->execute([
                    'userId' => $this->userId,
                    'orderId' => $this->response->payload->orderId,
                    'paymentUrl' => $this->response->payload->paymentUrl,
                    'sessionId' => $this->response->payload->sessionId,
                    'code' => $this->response->code
                ]);
            } catch (PDOException $ex) {
                $this->setLog('add', 'Failed to add, response: ' . json_encode($this->response));
                throw new Exception('Failed to add, response ' . json_encode($this->response));
            }
        } else {
            $this->setLog('add', 'Failed to add, empty response');
            throw new Exception('Failed to add, empty response');
        }
    }
}