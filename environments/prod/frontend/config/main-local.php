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
                'logowanie' => 'site/login',
                'rejestracja' => 'site/signup',
                'reset-hasla' => 'site/request-password-reset',
                'ustawienia' => 'settings/index',
                'wyloguj' => 'site/logout',
                'zmiana-hasla/<token:\w+>' => 'site/reset-password',
            ],
        ],
    ],
];
