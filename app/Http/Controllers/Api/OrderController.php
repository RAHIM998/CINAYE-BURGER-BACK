<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Burger;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class OrderController extends Controller
{
    //Méthode d'affichage des commandes
    public function index()
    {
        if (Auth::user()->isAdmin()){
            return $this->jsonResponse(true, "Liste des commandes ", Orders::all());
        }else{
            $userOrders = Orders::where('user_id', Auth::id())->get();
            return $this->jsonResponse(true, "Commande de l'utilisateur connecté", [$userOrders], 200);
        }
    }

    //Filtrage des commandes

    //Commandes du jours
    public function dailyOrders()
    {
        $todayOrders = Orders::whereDate('created_at', Carbon::today())->get();
        return $this->jsonResponse(true, "Liste des commandes du jour", $todayOrders);
    }

    //Commandes en cours
    public function pendingOrders()
    {
        $pendingOrders = Orders::where('status', 'pending')->get();
        return $this->jsonResponse(true, "Liste des commandes en cours", $pendingOrders);
    }

   //Méthode de sauvegarde d'une commande
    public function store(OrderRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $numberOrder = 'CMD' . date('ymd') . rand(1000, 9999);
            DB::beginTransaction();

            $amountOrder = 0;
            $burgerOrder = [];
            foreach ($validatedData['burgers'] as $burger) {
                $existBurger = Burger::findOrFail($burger['id']);
                if ($existBurger->quantity < $burger['quantity']) {
                    DB::rollBack();
                    return $this->jsonResponse(false, "Quantité insuffisante !", [], 500);
                } else {
                    $unitPrice = $existBurger->price;
                    $amountOrder += $unitPrice * $burger['quantity'];
                    $burgerOrder[$burger['id']] = [
                        'quantity' => $burger['quantity'],
                        'unitPrice' => $unitPrice,
                    ];
                }
            }

            // Création de la commande
            $order = Orders::create([
                'user_id' => Auth::user()->id,
                'numberOrder' => $numberOrder,
                'dateOrder' => date('Y-m-d'),
                'amountOrder' => $amountOrder,
                'addressLivraison' => $validatedData['addressLivraison'],
                'status' => "pending",
            ]);

            // Mise à jour du stock et attachement des produits à la commande
            foreach ($burgerOrder as $burgerId => $bg) {
                try {
                    $burgerToSave = Burger::findOrFail($burgerId);
                    $burgerToSave->decrement('quantity', $bg['quantity']);
                    $order->burgers()->attach($burgerToSave->id, $bg);
                } catch (Exception $exception){
                    DB::rollBack();
                    return $this->jsonResponse(false, "Erreur !", [], 500);
                }

            }

            DB::commit();

            return $this->jsonResponse(true, "Commande passée avec succès", $order, 201);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->jsonResponse(false, "Erreur de sauvegarde dans la BD", [], 500);
        }
    }

    //Méthode d'affichage des détails d'une commande
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $order = Orders::with('burgers')->findOrFail($id);
            return $this->jsonResponse(true, "Détails de la commande", $order);
        }catch (Exception $exception){
            return $this->jsonResponse(false, "Erreur !", [], 500);
        }
    }

    //Méthode de modification des commandes
    public function update(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $Order = Orders::findOrFail($id);

            // Validez le nouveau statut
            $newStatus = $request->input('status');
            $validStatuses = ['pending', 'paid', 'cancelled'];

            if (!in_array($newStatus, $validStatuses)) {
                return $this->jsonResponse(false, "Transition de statut non autorisée");
            }

            if ($newStatus == 'cancelled'){
                $this->destroy($id);
                return $this->jsonResponse(true, "Commande annulée avec succès !", $Order);
            }elseif ($newStatus === 'paid'){
                $Order->status = $newStatus;
                $Order->save();
            }

            return $this->jsonResponse(true, "Statut modifié avec succès !", $Order, 201);


        } catch (Exception $e) {
            return $this->jsonResponse(false, "Erreur de transisition du statut", [], 500);
        }
    }

    // Méthodes de suppression des commandes
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $Order = Orders::findOrFail($id);
            $Order->delete();

            return $this->jsonResponse(true, "Commande archivée avrc succès !", $Order);
        }catch (Exception $exception){
            return $this->jsonResponse(false, "Erreur !", $exception->getMessage(), 500);
        }
    }
}
