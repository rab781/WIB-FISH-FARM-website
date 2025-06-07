<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

// Import all common Laravel helper functions
use function view;
use function redirect;
use function response;
use function request;
use function route;
use function public_path;
use function storage_path;
use function env;
use function now;
use function abort;
use function back;
use function asset;
use function url;
use function config;
use function auth;
use function session;
use function cookie;
use function cache;
use function trans;
use function __;
use function app;
use function collect;
use function dd;
use function dump;

/**
 * Base Controller with Laravel helper functions imported
 */
class BaseController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
