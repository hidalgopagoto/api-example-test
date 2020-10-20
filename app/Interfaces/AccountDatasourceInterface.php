<?php
namespace App\Interfaces;

use App\Model\Account;

interface AccountDatasourceInterface
{
    public function findByCode(int $code): ?array;
}