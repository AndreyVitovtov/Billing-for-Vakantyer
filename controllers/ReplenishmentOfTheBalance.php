<?php

namespace Controller;

use DB\Database;
use Exception;
use Model\Balance;
use Model\BalancePackage;
use Model\Package;
use PDOException;

class ReplenishmentOfTheBalance
{
    private function error404()
    {
        header('Refresh: 0; url=/404.html');
    }

    /**
     * @throws Exception
     */
    public function packageSelection()
    {
        if (!isset($_REQUEST['package'])) $this->error404();
        $package = (new Package())->get($_REQUEST['package']);
        if (!$package) $this->error404();
        if($package->free && (new BalancePackage($_SESSION['userId']))->checkFree()) {
//            throw new Exception('Free top up already used');
            echo 'Free top up already used';
            die;
        }
        return $package;
    }

    /**
     * @throws Exception
     */
    public function replenishmentBank(Package $package)
    {
        $db = Database::instance()->getDbh();
        try {
            $db->beginTransaction();

            $balancePackage = (new BalancePackage(
                $_SESSION['userId'],
                $package->vacancyCost,
                0,
                $package->price,
                $package->id,
                ($package->free ? date('Y-m-d H:i:s', strtotime('+ 6 MONTH', time())) : null),
                'bank'
            ))->add();

            $balance = (new Balance($_SESSION['userId']))->add($balancePackage->price);

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            throw new Exception('Failed to top up balance');
        }
        echo $balance->balance . ' AZN';
    }

    public function replenishmentCard(Package $package)
    {
        ///
    }

    public function replenishmentFree(Package $package)
    {
        ///
    }
}