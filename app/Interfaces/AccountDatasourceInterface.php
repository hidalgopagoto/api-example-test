<?php
namespace App\Interfaces;

use App\Model\Account;

interface AccountDatasourceInterface
{
    public function findByCode(?int $code = null): ?array;
    public function deposit(int $code, float $amount): bool;
    public function transfer(int $origin, int $destination, float $amount): bool;
    public function withdraw(int $code, float $amount): bool;
    public function create(int $code): int;
    public function reset(): bool;
    public function getMaxAmountForWithdraw(): float;
}