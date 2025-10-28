<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Http\Resources\CompteResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Get the authenticated client
        $client = $request->user();

        // Check user role and apply appropriate filtering
        if ($client->isAdmin()) {
            // Admin can see all comptes
            $query = Compte::with('client');
        } else {
            // Regular client can only see their own comptes
            $query = Compte::with('client')->where('client_id', $client->id);
        }

        // Filtrage par type
        if ($request->has('type') && in_array($request->type, ['epargne', 'cheque'])) {
            $query->where('type', $request->type);
        }

        // Filtrage par statut
        if ($request->has('statut') && in_array($request->statut, ['actif', 'bloque', 'ferme'])) {
            $query->where('statut', $request->statut);
        }

        // Recherche par numéro ou nom du titulaire
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', '%' . $search . '%')
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('nom', 'like', '%' . $search . '%');
                  });
            });
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        // Pagination
        $perPage = $request->get('limit', 15);
        $comptes = $query->paginate($perPage);

        return response()->json([
            'data' => CompteResource::collection($comptes),
            'meta' => [
                'current_page' => $comptes->currentPage(),
                'per_page' => $comptes->perPage(),
                'total' => $comptes->total(),
                'last_page' => $comptes->lastPage(),
            ],
        ]);
    }
}