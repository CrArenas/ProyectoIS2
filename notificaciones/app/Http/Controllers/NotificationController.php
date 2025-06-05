<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;

class NotificationController extends Controller
{
    public function enviar(){ //Es público porque vamos a acceder a este por fuera de la clase
        //Obtener las credenciales de Twilio desde el archivo .env
        //Traer los datos que me da Twilio desde el .env. No se sube al repositorio, se ocultan así las credenciales
        $sid = env('TWILIO_SID');
        $token =env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token); //Instancia de la librería para que me envíe un nuevo mensaje, recibe el SID y el token

        $destino = '+573142760493';
        $mensaje = 'Se ha detectado una rata';

        //Enviar el mensaje de texto
        $twilio->messages->create( //Es utilizando ya el paquete como tal
            //Argumentos de aquí hasta );
            $destino, 
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $mensaje
            ]
        );
        //La respuesta que retornará la API
        return response()->json([
            'message' => 'Mensaje enviado correctamente'
            ]);
    }

    
}
