pipeline {
    agent {
        label 'MASTER'
    }
    stages {
        stage('SCM') {
            steps {
                cleanWs()
                dir('src') {
                    git 'https://github.com/kkretsch/lggr/'
                }
            }
        }
        stage('Test') {
            agent {
                dockerfile true
            }
            steps {
                sh 'node --version'
                sh 'svn --version'
            }
        }
    }
}
