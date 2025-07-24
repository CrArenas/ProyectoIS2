pipeline {
    agent any
    
    environment {
        GATEWAY_CONTAINER = 'gateway'
        DB_CONTAINER = 'mysql_db'
        NETWORK_NAME = 'proyectois2_red_publica'
    }
    
    stages {
        stage('Checkout') {
            steps {
                echo 'Checking out code...'
                checkout scm
            }
        }
        
        stage('Verify Services') {
            steps {
                echo 'Verifying that required services are running...'
                script {
                    sh '''
                        echo "Checking if Gateway container is running..."
                        docker ps | grep ${GATEWAY_CONTAINER} || (echo "ERROR: Gateway container not running" && exit 1)
                        
                        echo "Checking if Database container is running..."
                        docker ps | grep ${DB_CONTAINER} || (echo "ERROR: Database container not running" && exit 1)
                        
                        echo "All required services are running!"
                    '''
                }
            }
        }
        
        stage('Prepare Test Environment') {
            steps {
                echo 'Preparing test environment in existing Gateway container...'
                script {
                    sh '''
                        echo "Installing dev dependencies for testing..."
                        docker exec ${GATEWAY_CONTAINER} composer install --dev
                        
                        echo "Preparing test database..."
                        docker exec ${GATEWAY_CONTAINER} php artisan config:clear
                        docker exec ${GATEWAY_CONTAINER} php artisan cache:clear
                    '''
                }
            }
        }
        
        stage('Database Migration for Tests') {
            steps {
                echo 'Setting up test database...'
                script {
                    sh '''
                        echo "Running migrations for testing..."
                        docker exec ${GATEWAY_CONTAINER} php artisan migrate:fresh --seed --force
                    '''
                }
            }
        }
        
        stage('Run Unit Tests') {
            steps {
                echo 'Running unit tests in Gateway container...'
                script {
                    sh '''
                        echo "Executing PHPUnit Unit Tests..."
                        docker exec ${GATEWAY_CONTAINER} php artisan test --testsuite=Unit --stop-on-failure
                    '''
                }
            }
        }
        
        stage('Run Feature Tests') {
            steps {
                echo 'Running feature tests in Gateway container...'
                script {
                    sh '''
                        echo "Executing PHPUnit Feature Tests..."
                        docker exec ${GATEWAY_CONTAINER} php artisan test --testsuite=Feature --stop-on-failure
                    '''
                }
            }
        }
        
        stage('Run All Tests (Alternative)') {
            steps {
                echo 'Running all tests using php artisan test...'
                script {
                    sh '''
                        echo "Executing all tests with artisan..."
                        docker exec ${GATEWAY_CONTAINER} php artisan test --parallel
                    '''
                }
            }
        }
        
        stage('Generate Test Coverage') {
            steps {
                echo 'Generating test coverage report...'
                script {
                    sh '''
                        echo "Generating coverage report..."
                        docker exec ${GATEWAY_CONTAINER} vendor/bin/phpunit --coverage-html coverage --coverage-clover coverage.xml || echo "Coverage generation completed with warnings"
                    '''
                }
            }
        }
    }
    
    post {
        always {
            echo 'Cleaning up test artifacts...'
            script {
                sh '''
                    echo "Clearing caches after tests..."
                    docker exec ${GATEWAY_CONTAINER} php artisan cache:clear || true
                    docker exec ${GATEWAY_CONTAINER} php artisan config:clear || true
                '''
            }
        }
        
        success {
            echo 'All tests passed successfully! ✅'
            // Aquí puedes agregar notificaciones de éxito
        }
        
        failure {
            echo 'Tests failed! ❌'
            script {
                sh '''
                    echo "Showing recent logs for debugging..."
                    docker logs --tail 50 ${GATEWAY_CONTAINER} || true
                '''
            }
        }
        
        cleanup {
            echo 'Pipeline cleanup completed.'
        }
    }
}
