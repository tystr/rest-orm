pipeline {
  agent {
    docker {
      image 'composer/composer'
    }

  }
  stages {
    stage('Install Dependencies') {
      steps {
        sh 'composer install'
      }
    }
    stage('Test') {
      steps {
        sh './vendor/bin/phpunit'
      }
    }
  }
}