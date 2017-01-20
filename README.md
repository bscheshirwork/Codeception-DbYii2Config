# DbYii2Config
Connection 'Db' codeception module to 'Yii2' codeception module database settings

##NO MORE db settings in `codeception.yml`!

Delete duplicate settings `dsn`, `username`, `password`, look at this:

Example `backend/codeception.yml`
```
namespace: backend\tests
actor: Tester
paths:
    tests: tests
    log: tests/_output
    data: tests/_data
    support: tests/_support
    envs: tests/_envs
settings:
    bootstrap: _bootstrap.php
    colors: true
    memory_limit: 1024M
extensions:
    enabled:
        - Codeception\Extension\RunFailed
modules:
    config:
        Yii2:
            configFile: 'config/test-local.php'
```

Example `common/config/main-local.php` (included in `backend/config/test-local.php`)
```
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=yii2advanced',
            'username' => 'yii2advanced',
            'password' => 'yii2advanced',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
```

Example `backend/tests/acceptance.suite.yml`
```
class_name: AcceptanceTester
modules:
    enabled:
# See docker-codeception-run/docker-compose.yml: "ports" of service "nginx" is null; the selenium service named "firefox"
# See nginx-conf/nginx.conf: listen 80 for frontend; listen 8080 for backend
        - WebDriver:
            url: http://nginx:8080/
            host: firefox
            port: 4444
            browser: firefox
        - Yii2:
            part:
              - email
              - ORM
              - Fixtures
        - \bscheshirwork\DbYii2Config:
            dump: ../common/tests/_data/dump.sql #relative path from "codeception.yml"
```