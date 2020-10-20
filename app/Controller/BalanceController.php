<?php
namespace App\Controller;

use App\Http\Response;
use App\Model\Account;

class BalanceController
{
    /**
     *
     */
    public function show()
    {
        try {
            $accountId = $_GET['account_id'] ?? null;
            if (!$accountId) {
                throw new \Exception('Parameter account_id is required');
            }
            $response = new Response();
            $account = (new Account())->findByCode($accountId);
            if (!$account) {
                $response->json(0, 404);
                exit;
            }
            $response->json($account->getBalance());
            exit;
        } catch (\Exception $e) {
            // @TODO send to metrics tool
            $response = new Response();
            $response->json(['success' => false, 'message' => $e->getMessage()], $e->getCode());
        }
    }
}