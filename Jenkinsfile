pipeline {
    agent any
    
    // Nos traemos los .env que cargamos en Jenkins y los declaramos
    environment {
        GATEWAY_ENV = credentials('env-gateway')
        TRANSACTIONS_ENV = credentials('env-transacciones')
        NOTIFICATIONS_ENV = credentials('env-notificaciones')
    }
    
    stages {

    // Limpiamos el Workspace para que el Jenkinsfile salga bien
        stage('Limpiar el workspace') {
            steps {
                deleteDir()
            }
        }

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

        stage('Esperar MySQL') {
            steps {
                echo 'Esperando a que MySQL esté disponible...'
                sh '''
                    for i in {1..20}; do
                        if docker exec mysql_db mysqladmin ping -h"127.0.0.1" -uroot -proot --silent; then
                            echo "✅ MySQL está disponible"
                            break
                        fi
                        echo "⏳ Esperando MySQL... ($i)"
                        sleep 5
                    done
                '''
            }
        }

    // Cuadramos las credenciales en cada proyecto
        stage('Copiar la variable de entorno y la ubicamos como .env en el Gateway') {
            steps {
                dir('gateway') {
                    sh '''
                        cp "$GATEWAY_ENV" .env
                    '''
                }
            }
        }
        stage('Copiar la variable de entorno y la ubicamos como .env en el Transacciones') {
            steps {
                dir('transacciones') {
                    sh '''
                        cp "$TRANSACTIONS_ENV" .env
                    '''
                }
            }
        }
        stage('Copiar la variable de entorno y la ubicamos como .env en el Notificaciones') {
            steps {
                dir('notificaciones') {
                    sh '''
                        cp "$NOTIFICATIONS_ENV" .env
                    '''
                }
            }
        }
        
    // Instalamos dependencias en cada uno de los microservicios 
        stage('Instalar dependencias PHP en Gateway') {
            steps {
                sh '''
                    docker exec gateway composer update 
                '''
            }
        }
        stage('Instalar dependencias PHP en Transacciones') {
            steps {
                sh '''
                    docker exec transacciones composer update 
                '''
            }
        }
        stage('Instalar dependencias PHP en Notificaciones') {
            steps {
                sh '''
                    docker exec notificaciones composer update 
                '''
            }
        }
    // Generamos la APP_KEY de cada microservicio
        stage('Generar APP_KEY Gateway') {
            steps {
                sh '''
                    docker exec gateway php artisan key:generate
                '''
            }
        }

        stage('Generar APP_KEY Transacciones') {
            steps {
                sh '''
                    docker exec transacciones php artisan key:generate
                '''
            }
        }

        stage('Generar APP_KEY Notificaciones') {
            steps {
                sh '''
                    docker exec notificaciones php artisan key:generate
                '''
            }
        }

    // Ejecutamos las migraciones del Gateway
        stage('Ejecutar las migraciones del Gateway') {
            steps {
                    sh '''
                        docker exec gateway php artisan migrate:refresh 
                    '''
            }
        }
    // Ejecutamos los seeders del Gateway
        stage('Ejecutar los seeders del Gateway') {
            steps {
                sh '''
                    docker exec gateway php artisan db:seed 
                '''
            }
        }
    
    // Ejecutamos cada servicio
        stage('Levantar el servicio del Gateway') {
            steps {
                sh '''
                    docker exec -d gateway php artisan serve --host=0.0.0.0 --port=8000
                '''
            }
        }

        stage('Levantar el servicio de Transacciones') {
            steps {
                sh '''
                    docker exec -d transacciones php artisan serve --host=0.0.0.0 --port=8000
                '''
            }
        }

        stage('Levantar el servicio de Notificaciones') {
            steps {
                sh '''
                    docker exec -d notificaciones php artisan serve --host=0.0.0.0 --port=8000
                '''
            }
        }

    // Ejecutamos los test que se encuentran en el Gateway 
        stage('Ejecutar pruebas Gateway') {
            steps {
                    sh '''
                        docker exec gateway php artisan test
                    '''
                }
        }
    }
}
