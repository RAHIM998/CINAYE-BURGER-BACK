<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Orders;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    //Lister les paiements
    public function index()
    {
        //
    }

    //Sauvegarde de paiement
    public function store(Request $request): JsonResponse
    {
        try {

            $validatedData = $request->validated();

            $order = Orders::findOrFail($validatedData['order_id']);

            $payment = Payments::create([
                'order_id' => $validatedData['order_id'],
                'amountOrder' => $order->amountOrder,
                'payment_date' => Carbon::now()
            ]);

            return $this->jsonResponse(true, "Payment created", $payment, 201);

        }catch (\Exception $exception){
            return $this->jsonResponse(false, "Error", $exception->getMessage(), 500);
        }
    }


    //Voir les détails d'un paiement
    public function show(string $id)
    {
        //
    }

    //Modification de paiement
    public function update(Request $request, string $id)
    {
        //
    }

    //Suppression de paiement
    public function destroy(string $id)
    {
        //
    }
}
