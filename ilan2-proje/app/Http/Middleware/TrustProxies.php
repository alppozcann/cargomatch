<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    protected $proxies = '*'; // <<< Bunu EKLE!

    protected $headers = Request::HEADER_X_FORWARDED_ALL; // <<< Bunu da böyle yap!
}