pipeline {
    agent any
    
    // Nos traemos los .env que cargamos en Jenkins y los declaramos
    environment {
        GATEWAY_ENV = credentials('env-gateway')
        TRANSACTIONS_ENV = credentials('env-transactions')
        NOTIFICATIONS_ENV = credentials('env-notifications')
    }
    
    stages {
    // Clonamos el repositorio del proyecto
        stage('Clonar repositorio') {
            steps {
                git url: 'https://github.com/CrArenas/ProyectoIS2', branch: 'main'
            }
        }

    // Crear los contenedores y levantarlos
        stage('Construir contenedores') {
            steps {
                sh 'docker compose up --build'
            }
        }

    // Cuadramos las credenciales en cada proyecto
        stage('Copiar la variable de entorno y la ubicamos como .env en el Gateway') {
            steps {
                dir('gateway') {
                    sh "cp ${GATEWAY_ENV} .env"
                }
            }
        }
        stage('Copiar la variable de entorno y la ubicamos como .env en el Transacciones') {
            steps {
                dir('transacciones') {
                    sh "cp ${TRANSACTIONS_ENV} .env"
                }
            }
        }
        stage('Copiar la variable de entorno y la ubicamos como .env en el Notificaciones') {
            steps {
                dir('notificaciones') {
                    sh "cp ${NOTIFICATIONS_ENV} .env"
                }
            }
        }
        
    // Instalamos dependencias en cada uno de los microservicios 
        stage('Instalar dependencias PHP en Gateway') {
            steps {
                sh '''
                docker compose exec gateway composer install 
                '''
            }
        }
        stage('Instalar dependencias PHP en Transacciones') {
            steps {
                sh '''
                docker compose exec transacciones composer install 
                '''
            }
        }
        stage('Instalar dependencias PHP en Notificaciones') {
            steps {
                sh '''
                docker compose exec notificaciones composer install 
                '''
            }
        }
    // Generamos la APP_KEY de cada microservicio
        stage('Generar APP_KEY Gateway') {
            steps {
                sh '''
                docker compose exec gateway php artisan key:generate
                '''
            }
        }

        stage('Generar APP_KEY Transacciones') {
            steps {
                sh '''
                docker compose exec transacciones php artisan key:generate
                '''
            }
        }

        stage('Generar APP_KEY Notificaciones') {
            steps {
                sh '''
                docker compose exec notificaciones php artisan key:generate
                '''
            }
        }

    // Ejecutamos las migraciones del Gateway
        stage('Ejecutar las migraciones del Gateway') {
            steps {
                sh '''
                docker compose exec gateway php artisan migrate:refresh 
                '''
            }
        }
    // Ejecutamos los seeders del Gateway
        stage('Ejecutar los seeders del Gateway') {
            steps {
                sh '''
                docker compose exec gateway php artisan db:seed 
                '''
            }
        }
    
    // Ejecutamos cada servicio
        stage('Levantar el servicio del Gateway') {
            steps {
                sh '''
                docker compose exec gateway php artisan serve --host=0.0.0.0 --port=8000
                '''
            }
        }

        stage('Levantar el servicio de Transacciones') {
            steps {
                sh '''
                docker compose exec transacciones php artisan serve --host=0.0.0.0 --port=8000
                '''
            }
        }

        stage('Levantar el servicio de Notificaciones') {
            steps {
                sh '''
                docker compose exec notificaciones php artisan serve --host=0.0.0.0 --port=8000
                '''
            }
        }

    // Ejecutamos los test que se encuentran en el Gateway 
        stage('Ejecutar pruebas Gateway') {
            steps {
                sh '''
                docker compose exec gateway php artisan test
                '''
            }
        }
    }
}
