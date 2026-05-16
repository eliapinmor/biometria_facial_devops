<x-guest-layout>
    <div class="text-center p-6">
        <h2 class="text-xl font-bold mb-4">Verificación de Identidad</h2>
        <p class="mb-4 text-gray-600">Por favor, mira a la cámara para finalizar el acceso.</p>

        <video id="video" class="rounded-lg bg-black w-full h-64 mb-4" autoplay></video>
        <canvas id="canvas" class="hidden" width="640" height="480"></canvas>

        <form action="{{ route('verify.facial') }}" method="POST" id="form-facial">
            @csrf
            <input type="hidden" name="foto_base64" id="foto_base64">
            <button type="button" id="btn-capture" class="bg-indigo-600 text-white px-6 py-2 rounded-md w-full">
                📸 Escanear Rostro
            </button>
        </form>
    </div>

    <script>
        // Copia el mismo JS que usamos en el registro para capturar la foto
        // Pero haz que el formulario se envíe automáticamente tras capturar:
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const btnCapture = document.getElementById('btn-capture');
        const inputBase64 = document.getElementById('foto_base64');
        const form = document.getElementById('form-facial');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => { video.srcObject = stream; });

        btnCapture.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, 640, 480);
            inputBase64.value = canvas.toDataURL('image/jpeg');
            form.submit(); // Envío automático para que parezca un escáner real
        });
    </script>
</x-guest-layout>
