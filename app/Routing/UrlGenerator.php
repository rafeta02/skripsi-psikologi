<?php

namespace App\Routing;

use App\Support\HashId;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Support\Arr;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * @param  \Illuminate\Routing\Route  $route
     * @param  mixed  $parameters
     * @param  bool  $absolute
     */
    public function toRoute($route, $parameters, $absolute)
    {
        if (HashId::shouldHashRoute($route->getName())) {
            $parameters = HashId::encodeParameters(Arr::wrap($parameters));
        }

        return parent::toRoute($route, $parameters, $absolute);
    }
}
