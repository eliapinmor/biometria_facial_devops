<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Biométrico</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-4">Registro de Rostro</h2>
        <video id="video" class="rounded-lg bg-black w-full h-64 mb-4" autoplay></video>
        <canvas id="canvas" width="640" height="480" class="hidden"></canvas>

        <div class="flex flex-col gap-3">
            <button id="btn-capture" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                📸 Capturar Foto
            </button>

            <form action="{{ route('register.facial.store') }}" method="POST" id="form-facial">
                @csrf
                <input type="hidden" name="foto_base64" id="foto_base64">
                <button type="submit" id="btn-save" class="hidden bg-green-600 text-white px-4 py-2 rounded w-full">
                    ✅ Guardar y Continuar
                </button>
            </form>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const btnCapture = document.getElementById('btn-capture');
        const btnSave = document.getElementById('btn-save');
        const inputBase64 = document.getElementById('foto_base64');

        // Acceder a la webcam del portátil
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => { video.srcObject = stream; })
            .catch(err => { alert("Error al acceder a la cámara: " + err); });

        // Capturar la imagen
        btnCapture.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, 640, 480);

            // Convertir a Base64 y mostrar botón de guardar
            const dataURL = canvas.toDataURL('image/jpeg');
            inputBase64.value = dataURL;

            btnSave.classList.remove('hidden');
            btnCapture.innerText = "🔄 Repetir Foto";
            alert("¡Foto capturada con éxito!");
        });
    </script>
</body>
</html>
