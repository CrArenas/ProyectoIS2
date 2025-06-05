<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FlaskController extends Controller
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('MICROSERVICE_FLASK');
        $this->apiKey = env('API_KEY');
    }
    /**
     * Display a listing of the resource.
     */
    


    public function predecirFraude(Request $request)
    {
        // Validar que el request tenga los datos correctos
        $user = Auth::id();
        $validatedData = $request->validate([
            'V1' => 'required|numeric',
            'V2' => 'required|numeric',
            'V3' => 'required|numeric',
            'V4' => 'required|numeric',
            'V5' => 'required|numeric',
            'V6' => 'required|numeric',
            'V7' => 'required|numeric',
            'V8' => 'required|numeric',
            'V9' => 'required|numeric',
            'V10' => 'required|numeric',
            'V11' => 'required|numeric',
            'V12' => 'required|numeric',
            'V13' => 'required|numeric',
            'V14' => 'required|numeric',
            'V15' => 'required|numeric',
            'V16' => 'required|numeric',
            'V17' => 'required|numeric',
            'V18' => 'required|numeric',
            'V19' => 'required|numeric',
            'V20' => 'required|numeric',
            'V21' => 'required|numeric',
            'V22' => 'required|numeric',
            'V23' => 'required|numeric',
            'V24' => 'required|numeric',
            'V25' => 'required|numeric',
            'V26' => 'required|numeric',
            'V27' => 'required|numeric',
            'V28' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);

        $url = $this->apiUrl . '/predict';

        $response = Http::withHeaders(['X-API-KEY'=> $this->apiKey, 'UserId'=> $user])->post($url, $validatedData);

        if ($response->successful()) {
            return response()->json($response->json(), $response->status());
        }

        return response()->json([
            'error' => 'No se pudo obtener una respuesta válida del API Flask',
            'status' => $response->status(),
            'body' => $response->body()
        ], $response->status());
    }
}