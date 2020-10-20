<?php
namespace App\Http;

class Response
{
    /**
     * @param array|null $data
     * @param int $returnCode
     */
    public function json($data = [], int $returnCode = 200): void
    {
        http_response_code($returnCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}