pipeline {
    agent any

    stages {
        stage('Descargar código') {
            steps {
                // Jenkins clona automáticamente la última versión desde GitHub
                checkout scm
            }
        }

        stage('Validar PHP') {
            steps {
                echo 'Comprobando la sintaxis de los archivos PHP...'
                // Escanea tus archivos de Laravel para asegurarse de que no hay fallos de sintaxis antes de tocar Docker
                sh 'find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \\;'
            }
        }

        stage('Desplegar Proyecto') {
            steps {
                echo 'Actualizando y levantando contenedores en Docker...'
                // Relevanta tus contenedores con los últimos cambios que hayas subido
                sh 'docker compose up -d --build'
            }
        }
    }
}
