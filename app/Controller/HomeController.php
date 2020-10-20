<?php
namespace App\Controller;

use App\Http\Response;

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
        // @TODO reset data
        $response = new Response();
        $response->json(['success' => true, 'message' => 'Reset data']);
    }
}