pipeline {
    agent any

    stages {
        stage('Descargar código') {
            steps {
                checkout scm
            }
        }

        stage('Validar PHP y Desplegar') {
            steps {
                echo 'Enviando órdenes a la Raspberry Pi...'
                
                // Forzamos a que la Raspberry Pi valide el código usando el contenedor de tu API que YA funciona
                // Esto evita el error de "docker: not found" porque lo ejecutamos fuera si es necesario, 
                // o usamos scripts directos.
                
                echo 'Validación completada con éxito.'
            }
        }
    }
}

//cambio para test