<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

<div class="mt-4 p-4 bg-gray-50 rounded-lg border">
    <x-input-label value="Verificación Facial" class="mb-2" />
    <video id="video" class="rounded-lg bg-black w-full h-48 object-cover mb-2" autoplay></video>
    <canvas id="canvas" width="640" height="480" class="hidden"></canvas>
 
    <button type="button" id="btn-snap" class="w-full inline-flex justify-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs uppercase tracking-widest font-semibold">
        📸 Capturar Rostro para Acceder
    </button>

    <input type="hidden" name="foto_base64" id="foto_base64" required>
    <x-input-error :messages="$errors->get('foto_base64')" class="mt-2" />
</div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const btnSnap = document.getElementById('btn-snap');
    const inputBase64 = document.getElementById('foto_base64');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => { video.srcObject = stream; });

    btnSnap.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, 640, 480);
        inputBase64.value = canvas.toDataURL('image/jpeg', 0.8);
        alert("Rostro capturado. Ahora puedes iniciar sesión.");
    });
</script>
</x-guest-layout>
