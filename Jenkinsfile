pipeline {
    agent any
    
    // Nos traemos los .env que cargamos en Jenkins y los declaramos
    environment {
        GATEWAY_ENV = credentials('env-gateway')
        TRANSACTIONS_ENV = credentials('env-transacciones')
        NOTIFICATIONS_ENV = credentials('env-notificaciones')
    }
    
    stages {
        
        stage('limpiar'){
            steps{
                sh 'rm -rf ./*'
            }
        }
        
        stage('Clonar repositorio') {
            steps {
                git url: 'https://github.com/CrArenas/ProyectoIS2', branch: 'main'
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
        
        stage('Construir contenedores') {
            steps {
                sh 'docker-compose up -d --build'
            }
        }
        
        stage('Tests - Gateway en contenedor') {
            steps {
                sh 'docker exec gateway php artisan test'
            }
        }

        
        
        

    
    }
}
