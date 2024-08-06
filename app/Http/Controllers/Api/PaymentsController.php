<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class PaymentsController extends Controller
{
    //-----------------------------------------------------Lister les paiements----------------------------------------------------------------------
    public function index()
    {
        try {
            $paie = Payments::all();
            return $this->jsonResponse(true, "Liste des paiements", $paie);
        }catch (Exception $exception){
            return $this->jsonResponse(false, "Error !", $exception);
        }
    }

    //----------------------------------------------------------Payements du jours-------------------------------------------------------------------
    public function dailyPayments()
    {
        $todayPayments = Payments::whereDate('created_at', Carbon::today())->get();
        return $this->jsonResponse(true, "Liste des commandes du jour", $todayPayments);
    }

    //-------------------------------------------------------------Sauvegarde de paiement-------------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        try {
            $order = Orders::findOrFail($request['order_id']);
            if ($order){
                $payment = Payments::create([
                    'orders_id' => $request['order_id'],
                    'amountOrder' => $order->amountOrder,
                    'payment_date' => Carbon::now()
                ]);

            }

            return $this->jsonResponse(true, "Payment created", $payment, 201);

        }catch (\Exception $exception){
            return $this->jsonResponse(false, "Error", $exception->getMessage(), 500);
        }
    }


    //--------------------------------------------------------Voir les détails d'un paiement------------------------------------------------------------
    public function show(string $id)
    {
        try {
            $payment = Payments::with('orders')->findOrFail($id);
            return $this->jsonResponse(true, "Payment found", $payment);
        }catch (Exception $exception){
            return $this->jsonResponse(false, "Error !", $exception);
        }

    }

    //--------------------------------------------------------------Modification de paiement------------------------------------------------------------
    public function update(Request $request, string $id)
    {
        //
    }

    //-------------------------------------------------------------Suppression de paiement--------------------------------------------------------------
    public function destroy(string $id)
    {
        //
    }
}
