<?php
namespace App\Controller;

use App\Http\Response;
use App\Model\Account;

class HomeController
{
    /**
     *
     */
    public function index()
    {
        $response = new Response();
        $response->json(['success' => true, 'message' => 'Welcome to our API']);
    }

    /**
     *
     */
    public function reset()
    {
        $account = new Account();
        $account->reset();
        http_response_code(200);
        echo "OK";
        exit;
    }
}