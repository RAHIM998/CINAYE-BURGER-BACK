<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BurgerRequest;
use App\Models\Burger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class BurgerController extends Controller
{
    //Liste des burgers
    public function index()
    {
        try {
            $burger = Burger::Paginate(5);
            return $this->jsonResponse(true, "Liste des produits", $burger, 200);
        }catch (Exception $exception){
            return $this->jsonResponse(false, "Liste des produits", $exception->getMessage(), 400);
        }
    }

    //Sauvegarde des burger
    public function store(BurgerRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image')) {
                $image = $this->imageToBlob($request->file('image'));
                $validatedData['image'] = $image;
            }

            $burger = Burger::create($validatedData);
            return $this->jsonResponse(true, "Burger ajouté avec succuès !", $burger, 201);
        }catch (\Exception $exception){
            return $this->jsonResponse(false, "Error", $exception->getMessage(), 500);
        }

    }

    //Détails des burgers
    public function show(string $id)
    {
        try {
            $burger = Burger::findOrFail($id);
            if ($burger){
                return $this->jsonResponse(true, "Burger", $burger, 200);
            }else{
                return $this->jsonResponse(false, "Burger", $burger, 404);
            }
        }catch (Exception $exception){
            return $this->jsonResponse(false, "Error", $exception->getMessage(), 500);
        }
    }

    //Modification des des détails de burger
    public function update(BurgerRequest $request, string $id): \Illuminate\Http\JsonResponse
    {

        try {
            $validatedData = $request->validated();

            $burgertoupdate = Burger::findOrFail($id);

            if ($burgertoupdate) {
                $burgertoupdate->update($validatedData);
                return $this->jsonResponse(true, "Produit modifié avec succès !", $burgertoupdate, 200);
            } else {
                return $this->jsonResponse(false, "Produit n'existe pas !", [], 404);
            }
        } catch (Exception $exception) {
            return $this->jsonResponse(false, "Error", $exception->getMessage(), 500);
        }
    }

    //Suppression des burger
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $productDelete = Burger::findOrFail($id);

                // Supprimer le produit
                $productDelete->delete();

                return $this->jsonResponse(true, "Burger archivé avec succès !", $productDelete, 200);

        } catch (Exception $e) {
            return $this->jsonResponse(false, "Error", $e->getMessage(), 500);
        }
    }

    // Visualisation des burgers archivés
    public function archivedBurger(): \Illuminate\Http\JsonResponse
    {
        try {
            $deletedBurgers = Burger::onlyTrashed()->get();
            return $this->jsonResponse(true, "Liste des burgers archivés", $deletedBurgers, 200);
        } catch (Exception $exception) {
            return $this->jsonResponse(false, "Erreur lors de la récupération des burgers archivés", $exception->getMessage(), 500);
        }
    }

    // Récupération d'un burger archivé
    public function restoreBurger(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $burger = Burger::withTrashed()->findOrFail($id);
            $burger->restore();
            return $this->jsonResponse(true, "Burger restauré avec succès", $burger, 200);
        } catch (ModelNotFoundException $exception) {
            return $this->jsonResponse(false, "Burger non trouvé dans les éléments supprimés", [], 404);
        } catch (Exception $exception) {
            return $this->jsonResponse(false, "Erreur lors de la restauration du burger", $exception->getMessage(), 500);
        }
    }
}
