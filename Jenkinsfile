pipeline {
    agent any

    stages {
        stage('Descargar código') {
            steps {
                checkout scm
            }
        }

        stage('Validar PHP') {
            // Le decimos a Jenkins que ejecute esta etapa DENTRO de un contenedor PHP directamente
            agent {
                docker { image 'php:8.2-cli-alpine' }
            }
            steps {
                echo 'Validando la sintaxis de los archivos PHP de forma nativa...'
                sh 'find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \\;'
            }
        }

        stage('Desplegar Proyecto') {
            steps {
                echo 'Saltando temporalmente el reinicio para verificar que valida bien...'
                // Si la validación pasa, esta etapa se ejecutará
            }
        }
    }
}

//cambio para testear jenkins