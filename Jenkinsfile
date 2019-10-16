application = "seller-center-sdk"

pipeline {
    agent any

    stages {
        stage("Build") {
            steps {
                parallel(
                    "Configure": {
                        sh "mkdir -p reports"
                    },

                    "PHP": {
                        sh "composer install"
                    }
                )
            }
        }

        stage("Test") {
            steps {
                parallel(
                    "php-cs-fixer": {
                        sh "php vendor/bin/php-cs-fixer fix --dry-run -vv --format=junit > reports/php-cs-fixer.xml"
                    },

                    "phpunit": {
                        sh "php vendor/bin/phpunit --log-junit reports/phpunit.xml"
                    },

                    "phpstan": {
                        sh "php vendor/bin/phpstan analyse"
                    }
                )
            }
        }
    }

    post {
        always {
            junit "reports/**/*.xml"
        }
    }
}
