<?php
namespace App\Datasource;

abstract class AbstractDatasource
{
    protected function getConnection()
    {
        // @TODO get only one connection
        return new \PDO("mysql:host=mysql;dbname=api_example_test", "default", "secret");
    }
}