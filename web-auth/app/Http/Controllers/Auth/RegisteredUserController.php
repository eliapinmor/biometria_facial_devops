<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse 

{ 

    $request->validate([ 

        'name' => ['required', 'string', 'max:255'], 

        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class], 

        'password' => ['required', 'confirmed', Rules\Password::defaults()], 

        'foto_base64' => ['required', 'string'], // La foto de la cámara 

    ]); 

 

    // Lógica para guardar la foto física 

    $fotoData = str_replace(['data:image/jpeg;base64,', ' '], ['', '+'], $request->foto_base64);
$nombreArchivo = 'referencia_' . time() . '.jpg';
$rutaRelativa = 'fotos_faciales/' . $nombreArchivo; // <--- ESTA ES LA RUTA CLAVE

// 2. Guardamos físicamente
Storage::disk('public')->put($rutaRelativa, base64_decode($fotoData));

// 3. Guardamos en la BD
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'foto_facial' => $rutaRelativa, // <--- AQUÍ: Asegúrate que no pasas solo 'fotos_faciales/'
]);
 

    event(new Registered($user)); 

    Auth::login($user); 

 

    return redirect(route('dashboard', absolute: false)); 

} }
