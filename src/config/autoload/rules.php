<?php
return [
    'rules' => [
        '/v1/auth/login/{type}' => [
            'subject' => [
                'description' => '用户名/邮箱/手机',
                'type' => 'string',
                'min' => 3,
                'max' => 0,
                'required' => true,
            ],
            'password' => [
                'description' => '密码',
                'type' => 'string',
                'min' => 6,
                'max' => 0,
                'required' => true,
            ],
            '__permission' => 'none',
            '__encrypted' => true,
        ],
        '/v1/auth/signup/username' => [
            'username' => [
                'description' => '用户名',
                'type' => 'string',
                'min' => 3,
                'max' => 0,
                'required' => true,
            ],
            'password' => [
                'description' => '密码',
                'type' => 'string',
                'min' => 6,
                'max' => 0,
                'required' => true,
            ],
            '__permission' => 'none',
            '__encrypted' => true,
        ],
        '/v1/auth/logout' => [
            '__permission' => 'none',
            '__encrypted' => false,
        ],
        '/v1/auth/captcha' => [
            'width' => [
                'description' => '宽度',
                'type' => 'integer',
                'min' => 32,
                'max' => 320,
                'default' => 180,
            ],
            'height' => [
                'description' => '高度',
                'type' => 'integer',
                'min' => 32,
                'max' => 320,
                'default' => 64,
            ],
            'raw' => [
                'description' => 'raw',
                'type' => 'boolean',
                'default' => true,
            ],
            'uuid' => [
                'description' => 'UUID',
                'type' => 'string',
                'default' => '',
            ],
            '__permission' => 'none',
            '__encrypted' => false,
        ],
        '/v1/auth/pubkey' => [
            'raw' => [
                'description' => '是否输出纯文本',
                'type' => 'boolean',
                'default' => false,
            ],
            '__permission' => 'none',
            '__encrypted' => false,
        ],
        '/v1/sum' => [
            'a' => [
                'description' => '数字 a',
                'type' => 'integer',
                'required' => true,
            ],
            'b' => [
                'description' => '数字 b',
                'type' => 'integer',
                'required' => true,
            ],
            '__permission' => 'none',
            '__encrypted' => false,
        ],
    ],
    'permissions' => [
    ],
];