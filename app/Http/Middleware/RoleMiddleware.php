<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Guard;

class RoleMiddleware extends \Spatie\Permission\Middleware\RoleMiddleware
{
    // This class extends the Spatie RoleMiddleware for registration in Kernel.php
}

