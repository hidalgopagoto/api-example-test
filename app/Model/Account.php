<?php
namespace App\Model;

use App\Datasource\AccountDatasource;
use App\Interfaces\AccountDatasourceInterface;

class Account
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var float
     */
    protected $balance;

    /**
     * @var AccountDatasourceInterface
     */
    protected $datasource;

    public function __construct()
    {
        $this->datasource = new AccountDatasource();
    }

    /**
     * @param int|null $code
     * @return Account
     */
    public function findByCode(int $code = null)
    {
        $accountData = $this->datasource->findByCode($code);
        if ($accountData) {
            $account = new self();
            $account->setBalance($accountData['balance'] ?? 0);
            $account->setCode($code);
            return $account;
        }
        return null;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @param AccountDatasourceInterface $datasource
     */
    public function setDatasource(AccountDatasourceInterface $datasource): void
    {
        $this->datasource = $datasource;
    }
}