<?php
namespace App\Datasource;

use App\Interfaces\AccountDatasourceInterface;
use App\Model\Account;

class AccountDatasource extends AbstractDatasource implements AccountDatasourceInterface
{

    private static $maxLimitForWithdraw = 2;

    /**
     * @param int|null $code
     * @return Account|null
     */
    public function findByCode(?int $code = null): ?array
    {
        $connection = $this->getConnection();
        $rs = $connection->prepare("SELECT code, balance FROM account WHERE code = ?");
        $rs->bindParam(1, $code);
        $rs->execute();
        if ($rs->rowCount() > 0) {
            return $rs->fetch(\PDO::FETCH_ASSOC);
        }
        return null;
    }

    /**
     * @return float
     */
    public function getMaxAmountForWithdraw(): float
    {
        return self::$maxLimitForWithdraw;
    }

    /**
     * @param int $code
     * @param float $amount
     * @return bool
     */
    public function deposit(int $code, float $amount): bool
    {
        $connection = $this->getConnection();
        $rs = $connection->prepare("UPDATE account SET balance = balance + ? WHERE code = ?");
        $rs->bindParam(1, $amount);
        $rs->bindParam(2, $code);
        $rs->execute();
        return true;
    }

    /**
     * @param int $code
     * @return int
     */
    public function create(int $code): int
    {
        $connection = $this->getConnection();
        $rs = $connection->prepare("INSERT INTO account(code) VALUES (?)");
        $rs->bindParam(1, $code);
        $rs->execute();
        return $connection->lastInsertId();
    }

    /**
     * @return bool
     */
    public function reset(): bool
    {
        $connection = $this->getConnection();
        $rs = $connection->prepare("TRUNCATE account");
        $rs->execute();
        return true;
    }

    /**
     * @param int $code
     * @param float $amount
     * @return bool
     */
    public function withdraw(int $code, float $amount): bool
    {
        $connection = $this->getConnection();
        $rs = $connection->prepare("UPDATE account SET balance = balance - ? WHERE code = ?");
        $rs->bindParam(1, $amount);
        $rs->bindParam(2, $code);
        $rs->execute();
        return true;
    }

    /**
     * @param int $origin
     * @param int $destination
     * @param float $amount
     * @return bool
     */
    public function transfer(int $origin, int $destination, float $amount): bool
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $rs = $connection->prepare("UPDATE account SET balance = balance + ? WHERE code = ?");
            $rs->bindParam(1, $amount);
            $rs->bindParam(2, $destination);
            $rs->execute();
            $rs = $connection->prepare("UPDATE account SET balance = balance - ? WHERE code = ?");
            $rs->bindParam(1, $amount);
            $rs->bindParam(2, $origin);
            $rs->execute();
            $connection->commit();
            return true;
        } catch (\Exception $e) {
            $connection->rollBack();
            return false;
        }
    }
}