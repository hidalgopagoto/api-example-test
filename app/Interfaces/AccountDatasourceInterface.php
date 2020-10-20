<?php
namespace App\Interfaces;

use App\Model\Account;

interface AccountDatasourceInterface
{
    public function findByCode(?int $code = null): ?array;
    public function deposit(int $code, float $amount): void;
    public function transfer(int $origin, int $destination, float $amount): void;
    public function withdraw(int $code, float $amount): void;
    public function create(int $code): int;
    public function reset(): void;
}