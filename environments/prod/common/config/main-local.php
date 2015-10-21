<?php
return [
    'language' => 'pl-PL',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=nzs',
            'username' => 'root',
            'password' => 'alfa155',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'btn' => 'button.php',
                        'lbl' => 'label.php',
                        'msg' => 'message.php',
                    ]
                ],
            ],
        ],
    ],
];
