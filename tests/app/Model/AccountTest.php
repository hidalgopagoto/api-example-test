<?php

use App\Datasource\AccountDatasource;
use App\Model\Account;
use PHPUnit\Framework\TestCase;

final class AccountTest extends TestCase
{
    /**
     *
     */
    function testFindByCodeNull()
    {
        $datasource = $this->createMock(AccountDatasource::class);
        $datasource->method('findByCode')->willReturn(null);
        $account = new Account();
        $account->setDatasource($datasource);
        $result = $account->findByCode(null);
        $this->assertNull($result);
    }

    /**
     *
     */
    function testFindByCode()
    {
        $datasource = $this->createMock(AccountDatasource::class);
        $datasource->method('findByCode')->willReturn(['balance' => 5]);
        $account = new Account();
        $account->setDatasource($datasource);
        $result = $account->findByCode(1);
        $this->assertEquals(1, $result->getCode());
        $this->assertEquals(5, $result->getBalance());
    }

    /**
     *
     */
    function testDeposit()
    {
        $datasource = $this->createMock(AccountDatasource::class);
        $datasource->method('findByCode')->willReturn(['balance' => 10]);
        $datasource->method('deposit')->willReturn(true);
        $account = new Account();
        $account->setDatasource($datasource);
        $result = $account->findByCode(1);
        $result->deposit(5);
        $this->assertEquals(15, $result->getBalance());
    }

    /**
     *
     */
    function testWithdraw()
    {
        $datasource = $this->createMock(AccountDatasource::class);
        $datasource->method('findByCode')->willReturn(['balance' => 10]);
        $datasource->method('withdraw')->willReturn(true);
        $account = new Account();
        $account->setDatasource($datasource);
        $result = $account->findByCode(1);
        $result->withdraw(5);
        $this->assertEquals(5, $result->getBalance());
    }

    /**
     *
     */
    function testTransfer()
    {
        $datasource = $this->createMock(AccountDatasource::class);
        $datasource->method('findByCode')->willReturn(['balance' => 10]);
        $datasource->method('transfer')->willReturn(true);
        $account = new Account();
        $account->setDatasource($datasource);
        $origin = $account->findByCode(1);
        $destination = $account->findByCode(2);
        $origin->transfer($destination, 5);
        $this->assertEquals(5, $origin->getBalance());
        $this->assertEquals(15, $destination->getBalance());
    }
}