<?php 

 

namespace App\Http\Controllers\Auth; 

 

use App\Http\Controllers\Controller; 

use App\Http\Requests\Auth\LoginRequest; 

use Illuminate\Http\RedirectResponse; 

use Illuminate\Http\Request; 

use Illuminate\Support\Facades\Auth; 

use Illuminate\Support\Facades\Http; // Importante para la IA 

use Illuminate\Support\Facades\Storage; 

use Illuminate\View\View; 

 

class AuthenticatedSessionController extends Controller 

{ 

    public function create(): View 

    { 

        return view('auth.login'); 

    } 

 

    public function store(LoginRequest $request): RedirectResponse  

{  

    // 1. Validar que la foto de la cámara esté presente  

    $request->validate([  

        'foto_base64' => 'required|string',  

    ]);  

 

    // 2. Autenticación tradicional (Email y Password)  

    $request->authenticate();  

 

    // Si el password es correcto, obtenemos el usuario  

    $user = Auth::user();  

 

    // 1. Limpiamos la ruta 

    $nombreArchivo = trim($user->foto_facial);  

 

    // 2. Intentamos encontrar el archivo en el disco 'public'  

    if (!str_contains($nombreArchivo, 'fotos_faciales/')) {  

        $pathFinal = 'fotos_faciales/' . $nombreArchivo;  

    } else {  

        $pathFinal = $nombreArchivo;  

    }  

 

    if (!Storage::disk('public')->exists($pathFinal)) {  

        Auth::logout();  

        return back()->withErrors(['foto_base64' => "Archivo de referencia no encontrado."]);  

    }  

 

    // 3. Preparar imágenes 

    $pathReferencia = Storage::disk('public')->path($pathFinal);  

 

// --- ADICIÓN DE SEGURIDAD --- 

// Verificamos que sea un archivo real y NO un directorio antes de leerlo 

if (is_dir($pathReferencia)) { 

    Auth::logout(); 

    return back()->withErrors(['foto_base64' => 'Error: La ruta de referencia es un directorio, no una imagen.']); 

} 

 

// Intentamos leer el contenido con seguridad 

$fileContents = @file_get_contents($pathReferencia); 

 

if ($fileContents === false) { 

    Auth::logout(); 

    return back()->withErrors(['foto_base64' => 'No se pudo leer la imagen de referencia.']); 

} 

 

$fotoReferenciaData = base64_encode($fileContents); 

$fotoReferenciaBase64 = 'data:image/jpeg;base64,' . 
$fotoReferenciaData; 

 

try {
    // 1. Preparamos el contenido binario de las imágenes
    // Quitamos el encabezado base64 para quedarnos solo con el dato puro
    $img1_raw = base64_decode(str_replace('data:image/jpeg;base64,', '', $fotoReferenciaBase64));
    $img2_raw = base64_decode(str_replace('data:image/jpeg;base64,', '', $request->foto_base64));

    // 2. Enviamos como ARCHIVOS (attach) en lugar de JSON
    $response = Http::timeout(60)
        ->attach('img1', $img1_raw, 'referencia.jpg') // Campo 'img1' como espera Python
        ->attach('img2', $img2_raw, 'captura.jpg')    // Campo 'img2' como espera Python
        ->post('http://127.0.0.1:8181/verify');

    $resultado = $response->json();

    if ($response->successful()) {
        $distancia = $resultado['distance'] ?? 1.0;
        
        // Con Facenet (que es el que tienes en main.py), 
        // una distancia menor a 0.40 suele ser positivo. 
        // Vamos a ser un poco laxos con 0.55 por la luz.
        if (($resultado['verified'] ?? false) || $distancia <= 0.55) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
    }

    Auth::logout();
    $distanciaMsg = isset($resultado['distance']) ? " (Distancia: " . round($resultado['distance'], 3) . ")" : "";
    return back()->withErrors(['foto_base64' => 'Identidad no verificada.' . $distanciaMsg]);

} catch (\Exception $e) {
    Auth::logout();
    return back()->withErrors(['foto_base64' => 'Error de conexión con la IA: ' . $e->getMessage()]);
}

} 

 

    public function destroy(Request $request): RedirectResponse 

    { 

        Auth::guard('web')->logout(); 

        $request->session()->invalidate(); 

        $request->session()->regenerateToken(); 

        return redirect('/'); 

    } 

} 
