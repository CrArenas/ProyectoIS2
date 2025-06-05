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
}
