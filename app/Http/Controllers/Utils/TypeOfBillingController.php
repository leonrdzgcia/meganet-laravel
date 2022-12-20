<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\TypeBilling;
use App\Models\ClientInternetService;
use App\Models\ClientVozService;
use Carbon\Carbon;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Support\Facades\Log;

const PROCESS = [
    '1' => 'recurrent',
    '2' => 'daily',
    '3' => 'custom'
];

class TypeOfBillingController extends Controller
{
    protected $clientService;

    public function __construct($clientService)
    {
        $this->clientService = $clientService;
    }

    public function getNewBalanceAndPriceByTypeOfBilling()
    {
        if (isset(PROCESS[$this->clientService->client->client_main_information->type_of_billing_id])) {
            $function = PROCESS[$this->clientService->client->client_main_information->type_of_billing_id];
            return $this->$function();
        }
        return null;
    }

    public function daily()
    {
        $clientService = $this->clientService;
        $balance = $clientService->client->balance;

        $price = $clientService->price;

        if (!$this->clientService->serviceHasIva()) {
            $price = $this->clientService->getNewPriceByIva();
        }
        if ($this->ifServiceHasDiscount()) {
            $price = $this->getNewPriceByDiscount();
        }

        $cost = round($price / Carbon::now()->daysInMonth, 2);
        if (TypeOfBillingController::ifHaveBalanceToPayService($clientService->client->balance->amount, $cost)) {
            return [
                'new_balance' => $balance->amount - $cost,
                'price' => $clientService->price,
                'cost' => $cost,
                'payment_in_time' => true
            ];
        }
    }

    public function custom()
    {
        $clientService = $this->clientService;
        $balance = $clientService->client->balance;

        $price = $clientService->price;

        if (!$this->clientService->serviceHasIva()) {
            $price = $this->clientService->getNewPriceByIva();
        }

        if ($this->ifServiceHasDiscount()) {
            $price = $this->getNewPriceByDiscount();
        }

        $cost = $price;
        if (TypeOfBillingController::ifHaveBalanceToPayService($balance->amount, $cost)) {
            return [
                'new_balance' => $balance->amount - $price,
                'price' => $clientService->price,
                'cost' => $cost,
                'payment_in_time' => true
            ];
        }else {
            if (Client::find($clientService->client_id)->PromisePayment()){
                return [
                    'new_balance' => $balance->amount - $price,
                    'price' => $clientService->price,
                    'cost' => $cost
                ];
            }
        }
    }

    public function recurrent()
    {
        $clientService = $this->clientService;
        $balance = $clientService->client->balance;
        $price = $clientService->price;

        $balanceAmount = $balance->amount;

        if ($balanceAmount == 0 or $balanceAmount == null){
            return null;
        }

        if (!$this->clientService->serviceHasIva()) {
            $price = $this->clientService->getNewPriceByIva();
        }

        if ($this->ifServiceHasDiscount()) {
            $price = $this->getNewPriceByDiscount();
        }

        $cost = $price;
        if (TypeOfBillingController::ifHaveBalanceToPayService($clientService->client->balance->amount, $price)) {

            return [
                'new_balance' => $balanceAmount - $price,
                'price' => $balanceAmount,
                'cost' => $cost,
                'payment_in_time' => true
            ];
        } else {

            if ($clientService->isDeployed()){

                return [
                    'new_balance' => $balanceAmount - $price,
                    'price' => $clientService->price,
                    'cost' => $cost
                ];
            }
        }
    }

    public function ifServiceHasDiscount()
    {
        return $this->clientService->discount && $this->clientService->discount_percent &&
            !is_null($this->clientService->start_date_discount) &&
            (\Carbon\Carbon::now()->format('Y-m-d\TH:i') >= $this->clientService->start_date_discount &&
                \Carbon\Carbon::now()->format('Y-m-d\TH:i') <= $this->clientService->end_date_discount);
    }

    public function getNewPriceByDiscount()
    {
        return $this->clientService->price - round($this->clientService->price * $this->clientService->discount_percent / 100, 2);
    }

    public static function ifHaveBalanceToPayService($balance, $price)
    {
        return ($balance >= $price && $balance != 0 && $price != 0);
    }

    // public function ifServiceHasIva()
    // {
    //     return !$this->clientService->internet->tax_include && $this->clientService->internet->tax;
    // }

    // public function getNewPriceByIva($price, $tax)
    // {
    //     return $price + ($price * $tax / 100);
    // }

    public function isDailyOrCustomTypeOfBilling()
    {
        return in_array($this->clientService->client->client_main_information->type_of_billing_id,
            [TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY, TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM]);
    }
}
