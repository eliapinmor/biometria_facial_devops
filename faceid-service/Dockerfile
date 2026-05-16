FROM python:3.9-slim 

# Instalamos dependencias del sistema necesarias para OpenCV y DeepFace 

RUN apt-get update && apt-get install -y \ 
    libgl1 \ 
    libglib2.0-0 \ 
    build-essential \ 
    curl \
    && rm -rf /var/lib/apt/lists/* WORKDIR /app

WORKDIR /app 

# Copiamos el archivo de requisitos e instalamos las librerías 

COPY requirements.txt . 

RUN pip install --no-cache-dir -r requirements.txt 


# Copiamos el resto del código (main.py, test_health.py...) 

COPY . . 

EXPOSE 5000 


# Comando para arrancar la API con Uvicorn 

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "5000"] 
