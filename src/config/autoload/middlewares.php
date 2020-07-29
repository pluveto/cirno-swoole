<?php

declare(strict_types=1);

use App\Middleware\AuthenticationFilter;
use App\Middleware\AuthorizationFilter;
use App\Middleware\BeforeAllFilter;
use App\Middleware\ParameterValidatingFilter;
use App\Middleware\RequestMappingFilter;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'http' => [
        BeforeAllFilter::class,
        RequestMappingFilter::class,
        AuthenticationFilter::class,
        AuthorizationFilter::class,
        ParameterValidatingFilter::class,
    ],
];
