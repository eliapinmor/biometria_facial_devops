pipeline {
    agent any

    stages {
        stage('Descargar código') {
            steps {
                // Descarga automática desde GitHub
                checkout scm
            }
        }

        stage('Validar PHP') {
            steps {
                echo 'Validando sintaxis PHP usando el contenedor de Jenkins...'
                // Como Jenkins no tiene PHP, usamos una imagen ligera de Docker en línea 
                // para que analice nuestro código sin romper nada
                sh 'docker run --rm -v $(pwd):/app -w /app php:8.2-cli-alpine find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \\;'
            }
        }

        stage('Desplegar Proyecto') {
            steps {
                echo 'Actualizando contenedores en la Raspberry Pi...'
                // Forzamos a que use el binario de Docker del host de forma segura
                sh 'docker compose up -d --build'
            }
        }
    }
}

//cambio para testear jenkins