<?php
namespace App\Controller;

use App\Http\Response;
use App\Model\Account;

class EventController
{
    /**
     *
     */
    public function run()
    {
        try {
            $body = json_decode(file_get_contents('php://input'), true);
            $actionType = $body['type'] ?? null;
            switch ($actionType) {
                case 'deposit':
                    $this->deposit($body);
                    break;
                case 'withdraw':
                    $this->withdraw($body);
                    break;
                case 'transfer':
                    $this->transfer($body);
                    break;
            }
            throw new \Exception('Action not found');
        } catch (\Exception $e) {
            $response = new Response();
            $response->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param array|null $body
     */
    private function deposit(?array $body = [])
    {
        $destination = $body['destination'] ?? null;
        $account = (new Account())->findByCode($destination);
        $amount = $body['amount'] ?? 0;
        if ($account) {
            $account->deposit($amount);
        } else {
            $account = new Account();
            $account->setCode($destination);
            $account->create();
            $account->deposit($amount);
        }
        $response = new Response();
        $response->json(['destination' => ['id' => $account->getCode(), 'balance' => $account->getBalance()]], 201);
        exit;
    }

    /**
     * @param array|null $body
     */
    private function withdraw(?array $body = [])
    {
        $origin = $body['origin'] ?? null;
        $account = (new Account())->findByCode($origin);
        if (!$account) {
            $response = new Response();
            $response->json(0, 404);
            exit;
        }
        $amount = $body['amount'] ?? 0;
        $account->withdraw($amount);
        $response = new Response();
        return $response->json(['origin' => ['id' => $origin, 'balance' => $account->getBalance()]], 201);
    }

    /**
     * @param array|null $body
     */
    private function transfer(?array $body = [])
    {
        $origin = $body['origin'] ?? null;
        $destination = $body['destination'] ?? null;
        $amount = $body['amount'] ?? null;
        $accountOrigin = (new Account())->findByCode($origin);
        if (!$accountOrigin) {
            $response = new Response();
            $response->json(0, 404);
            exit;
        }
        $accountDestination = (new Account())->findByCode($destination);
        if (!$accountDestination) {
            $accountDestination = new Account();
            $accountDestination->setCode($destination);
            $accountDestination->create();
        }
        $accountOrigin->transfer($accountDestination, $amount);
        $response = new Response();
        return $response->json(['origin' => ['id' => $origin, 'balance' => $accountOrigin->getBalance()], 'destination' => ['id' => $destination, 'balance' => $accountDestination->getBalance()]], 201);
    }
}