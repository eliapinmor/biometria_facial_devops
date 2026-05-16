<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Importante añadir esto
use Illuminate\Support\Facades\Storage;

class BiometricController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'foto_webcam' => 'required|image',
        ]);

        $user = auth()->user();

        // 1. Verificar si el usuario tiene una foto registrada
        if (!$user->foto_facial) {
            return response()->json(['error' => 'No tienes una foto de referencia registrada.'], 400);
        }

        $pathReference = storage_path('app/public/' . $user->foto_facial);

        // 2. Verificar si el archivo físico existe en el disco
        if (!file_exists($pathReference)) {
            return response()->json(['error' => 'La foto de referencia no existe en el servidor.'], 404);
        }

        $pathWebcam = $request->file('foto_webcam')->getPathname();

        try {
            // 3. Enviamos la petición al servicio de Python/Docker
            $response = Http::attach('img1', file_get_contents($pathReference), 'ref.jpg')
                ->attach('img2', file_get_contents($pathWebcam), 'webcam.jpg')
                ->post(env('FACIAL_SERVICE_URL'));

            $result = $response->json();

            // 4. LÓGICA DE VALIDACIÓN
            // Suponiendo que tu servicio devuelve ['verified' => true/false]
            if ($response->successful() && isset($result['verified']) && $result['verified'] === true) {
                // AQUÍ: Lógica si la cara coincide
                return response()->json([
                    'success' => true,
                    'message' => 'Identidad confirmada'
                ]);
            } else {
                // AQUÍ: Si no coincide o hay error
                return response()->json([
                    'success' => false,
                    'message' => 'La cara no coincide con el usuario registrado.'
                ], 401);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error conectando con el servicio facial: ' . $e->getMessage()], 500);
        }
    }
}
