<?php

namespace BitApps\AssistPro\HTTP\Middleware;

use BitApps\AssistPro\Config;
use BitApps\AssistPro\Core\Http\Response;

final class IsProCheckerMiddleware
{
    public function handle()
    {
        if (!Config::isPro()) {
            return Response::error('Please active with valid license.')->httpStatus(403);
        }

        return true;
    }
}
