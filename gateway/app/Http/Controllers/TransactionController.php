<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class TransactionController extends Controller
{

    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('MICROSERVICE_TRANSACTIONS');
        $this->apiKey = env('API_KEY');
    }

    public function index()
    {
        $url = $this->apiUrl . '/transactions/';
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])->get($url);
        return $response->json();
    }

    public function show()
    {
        $user = Auth::id();
        $url = $this->apiUrl . '/userstransactions/'. $user;
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])->get($url);
        return $response->json();
    }

    public function store(Request $request)
    {
        $url = $this->apiUrl . '/transactions/';
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])->post($url, $request->all());
        return $response->json();
    }

    public function destroy($id)
    {
        $url = $this->apiUrl . '/transactions/'. $id;
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])->delete($url);
        return $response->json();
    }

    public function userReport()
    {
        $id = Auth::id();
        $url = $this->apiUrl . '/userReport/'. $id;
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])->get($url);
        if ($response->failed()) {
            return response()->json(['error' => 'No se pudo generar el reporte'], 500);
        }
    
        // Obtener el contenido del PDF
        $pdfContent = $response->body();
    
        // Retornar el PDF al usuario desde el gateway
        return response()->make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporte.pdf"',
        ]);
    }

    public function adminUserReport($user)
    {
        $url = $this->apiUrl . '/userReport/'. $user;
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])->get($url);
        if ($response->failed()) {
            return response()->json(['error' => 'No se pudo generar el reporte'], 500);
        }
    
        // Obtener el contenido del PDF
        $pdfContent = $response->body();
    
        // Retornar el PDF al usuario desde el gateway
        return response()->make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporte.pdf"',
        ]);
    }

    public function adminReport()
    {
        $url = $this->apiUrl . '/adminReport/';
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])->get($url);
        $pdfContent = $response->body();
    
        // Retornar el PDF al usuario desde el gateway
        return response()->make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporte.pdf"',
        ]);


    }
}
