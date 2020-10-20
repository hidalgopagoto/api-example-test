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
    public function findByCode(?int $code = null)
    {
        $accountData = $this->datasource->findByCode($code);
        if ($accountData) {
            $account = new self();
            $account->setDatasource($this->datasource);
            $account->setBalance($accountData['balance'] ?? 0);
            $account->setCode($code);
            return $account;
        }
        return null;
    }

    /**
     * @param float|int $amount
     */
    public function deposit(float $amount = 0)
    {
        $this->datasource->deposit($this->getCode(), $amount);
        $this->setBalance($this->getBalance() + $amount);
    }

    /**
     * @param float|int $amount
     */
    public function withdraw(float $amount = 0)
    {
        $this->datasource->withdraw($this->getCode(), $amount);
        $this->setBalance($this->getBalance() - $amount);
    }

    /**
     * @param Account $accountDestination
     * @param float $amount
     */
    public function transfer(self $accountDestination, float $amount)
    {
        $this->datasource->transfer($this->getCode(), $accountDestination->getCode(), $amount);
        $this->setBalance($this->getBalance() - $amount);
        $accountDestination->setBalance($accountDestination->getBalance() + $amount);
    }

    /**
     *
     */
    public function reset()
    {
        $this->datasource->reset();
    }

    /**
     *
     */
    public function create()
    {
        $this->datasource->create($this->getCode());
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int|null $code
     * @throws \Exception
     */
    public function setCode(?int $code): void
    {
        if (!$code) {
            throw new \Exception('Code not valid');
        }
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance ? $this->balance : 0;
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