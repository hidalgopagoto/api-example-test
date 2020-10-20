<?php
namespace App\Datasource;

use App\Interfaces\AccountDatasourceInterface;
use App\Model\Account;

class AccountDatasource implements AccountDatasourceInterface
{

    private static $accounts = [
        100 => [
            'balance' => 20
        ],
    ];

    /**
     * @param int $code
     * @return Account|null
     */
    public function findByCode(int $code): ?array
    {
        return self::$accounts[$code] ?? null;
    }
}