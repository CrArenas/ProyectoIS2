<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // Ensure this model exists in the specified namespace
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::all();
        return response()->json($transactions);
    }

    public function store(Request $request, $user)
    {
        //$transaction = Transaction::create($request->all());
        $transaction = new Transaction();
        $transaction->user_id = $user;
        $transaction->amount = $request->amount;
        $transaction->V1 = $request->V1;
        $transaction->V2 = $request->V2;
        $transaction->V3 = $request->V3;
        $transaction->V4 = $request->V4;
        $transaction->V5 = $request->V5;
        $transaction->V6 = $request->V6;
        $transaction->V7 = $request->V7;
        $transaction->V8 = $request->V8;
        $transaction->V9 = $request->V9;
        $transaction->V10 = $request->V10;
        $transaction->V11 = $request->V11;
        $transaction->V12 = $request->V12;
        $transaction->V13 = $request->V13;
        $transaction->V14 = $request->V14;
        $transaction->V15 = $request->V15;
        $transaction->V16 = $request->V16;
        $transaction->V17 = $request->V17;
        $transaction->V18 = $request->V18;
        $transaction->V19 = $request->V19;
        $transaction->V20 = $request->V20;
        $transaction->V21 = $request->V21;
        $transaction->V22 = $request->V22;
        $transaction->V23 = $request->V23;
        $transaction->V24 = $request->V24;
        $transaction->V25 = $request->V25;
        $transaction->V26 = $request->V26;
        $transaction->V27 = $request->V27;
        $transaction->V28 = $request->V28;
        $transaction->save();
        return response()->json(['message'=> 'Se guardo']); 
    }

    public function show($id)
    {
        $transactions = Transaction::where('user_id', $id)->get();
        return response()->json($transactions); 
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return response()->json(['message' => 'Transacción eliminada']);
    }

     
    public function userReport($userId)
    {
        
        $user = User::find($userId);      
        $transactions = DB::table('transactions')
        ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('COUNT(*) as count'))
        ->where('user_id', $userId)
        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('day')
        ->orderByRaw("FIELD(day, 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO')")
        ->get();

        return $this->generatePdf($transactions, "Reporte de Transacciones Semanales ({$user->name})");
    }

    // Reporte para el administrador (todas las transacciones semanales)
    public function adminReport()
    {
        $transactions = DB::table('transactions')
        ->join('users', 'transactions.user_id', '=', 'users.id') // Unir con la tabla de usuarios
        ->select('users.name as user', DB::raw('COUNT(transactions.id) as count'))
        ->whereBetween('transactions.created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('users.id', 'users.name')
        ->orderBy('users.name')
        ->get();

        return $this->generateAdminPdf($transactions, "Reporte de Transacciones Semanales (Administrador)");
    }

    // Función para generar el PDF con gráficos
    private function generatePdf($transactions, $title)
    {
        $tableRows = "";
        foreach ($transactions as $transaction) {
            $tableRows .= "
                <tr>
                    <td>{$transaction->day}</td>
                    <td>{$transaction->count}</td>
                </tr>
            ";
        }

    // Crear HTML del PDF con tabla
    $html = "
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; }
                h1 { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid black; padding: 10px; text-align: center; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <h1>{$title}</h1>
            <p><strong>Semana:</strong> " . now()->startOfWeek()->format('d/m/Y') . " - " . now()->endOfWeek()->format('d/m/Y') . "</p>
            <table>
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Cantidad de Transacciones</th>
                    </tr>
                </thead>
                <tbody>
                    {$tableRows}
                </tbody>
            </table>
        </body>
        </html>
    ";

    // Generar PDF con DomPDF
    $pdf = Pdf::loadHTML($html);

    return Response::make($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename=\"reporte.pdf\"'
    ]);
    }
    
    private function generateAdminPdf($transactions, $title)
    {
            // Construir las filas de la tabla
            $tableRows = "";
            foreach ($transactions as $transaction) {
                $tableRows .= "
                    <tr>
                        <td>{$transaction->user}</td>
                        <td>{$transaction->count}</td>
                    </tr>
                ";
            }

            // Crear HTML del PDF con la tabla
            $html = "
                <html>
                <head>
                    <meta charset='utf-8'>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; }
                        h1 { margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid black; padding: 10px; text-align: center; }
                        th { background-color: #f2f2f2; }
                    </style>
                </head>
                <body>
                    <h1>{$title}</h1>
                    <p><strong>Semana:</strong> " . now()->startOfWeek()->format('d/m/Y') . " - " . now()->endOfWeek()->format('d/m/Y') . "</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Cantidad de Transacciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$tableRows}
                        </tbody>
                    </table>
                </body>
                </html>
            ";

            // Generar PDF con DomPDF
            $pdf = Pdf::loadHTML($html);

            return Response::make($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename=\"reporte_admin.pdf\"'
            ]);
     }

}
