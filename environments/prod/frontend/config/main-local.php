<?php
return [
    'components' => [
        'request' => [
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nS9ds67sdJJ34kasdmDSSA734FSDdsm',
        ],
        'urlManager' =>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'dodaj-organizacje' => 'group/create',
                'organizacje/<url:[\-\w]+>/napisz-wiadomosc' => 'group/mail',
                'organizacje/<url:[\-\w]+>' => 'group/index',
                'organizacje' => 'group/list',
                'logowanie' => 'site/login',
                'rejestracja' => 'site/signup',
                'reset-hasla' => 'site/request-password-reset',
                'panel' => 'panel/index',
                'wyloguj' => 'site/logout',
                'zmiana-hasla/<token:\w+>' => 'site/reset-password',
                '<url:[\-\w]+>' => 'page/index',
            ],
        ],
    ],
];