<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

	<div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
    <x-input-label for="camera" :value="__('Registro Facial Obligatorio')" class="mb-2" />

    <div class="relative inline-block w-full">
        <video id="video" class="rounded-lg bg-black w-full h-48 object-cover mb-2" autoplay></video>
        <canvas id="canvas" width="640" height="480" class="hidden"></canvas>

        <div id="photo-success" class="hidden absolute inset-0 flex items-center justify-center bg-green-500 bg-opacity-20 rounded-lg">
            <span class="bg-white p-2 rounded-full text-green-600 font-bold text-xl">✓ Foto Lista</span>
        </div>
    </div>

    <button type="button" id="btn-snap" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full justify-center">
        📸 Capturar Rostro
    </button>

    <input type="hidden" name="foto_base64" id="foto_base64" required>

    <x-input-error :messages="$errors->get('foto_base64')" class="mt-2" />
</div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const btnSnap = document.getElementById('btn-snap');
    const inputBase64 = document.getElementById('foto_base64');
    const successOverlay = document.getElementById('photo-success');

    // Activar la cámara del portátil
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Error cámara:", err);
            alert("No se pudo acceder a la cámara. Asegúrate de usar HTTPS o Ngrok.");
        });

    // Capturar el frame
    btnSnap.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        // Dibujamos la imagen actual del video en el canvas
        context.drawImage(video, 0, 0, 640, 480);

        // Convertimos a Base64 (JPG para ahorrar espacio)
        const dataURL = canvas.toDataURL('image/jpeg', 0.8);

        // Guardamos en el input oculto
        inputBase64.value = dataURL;

        // Feedback visual
        successOverlay.classList.remove('hidden');
        btnSnap.innerText = "🔄 Repetir Captura";
        btnSnap.classList.replace('bg-gray-800', 'bg-indigo-600');
    });
</script>
</x-guest-layout>
