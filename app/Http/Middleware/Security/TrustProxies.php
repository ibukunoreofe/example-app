<?php

namespace App\Http\Middleware\Security;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * Trust all proxies, this is very important else it won't trust the proto proxy
     * @var array<int, string>|string|null
     */
//    protected $proxies = '*';

    // use this if security concern to prevent IP Spoofing
    // Multiple proxies. The IP of the docker network, reverse proxies or load balancers
    protected $proxies = ['192.168.1.0/24', '172.21.0.0/16'];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
