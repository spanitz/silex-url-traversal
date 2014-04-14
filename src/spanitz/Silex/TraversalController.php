<?php
namespace spanitz\Silex;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class TraversalController
{
    public function dispatch($url, Request $request, Application $app)
    {
        $prefix = $request->get('_route_params')['prefix'];

        $root = $app['traversal.root_factory'][$prefix]();
        $controller = $app['traversal.controller'][$prefix];

        $path = preg_split('/\//', $url, -1, PREG_SPLIT_NO_EMPTY);
        $resource = $this->traverse($root, $path);

        if ($resource) {
            return $controller($request, $resource);
        }

        return $app->abort(404);
    }

    protected function traverse ($resource, $path)
    {
        if (count($path) > 0) {
            $segment = array_shift($path);
            if (isset($resource[$segment])) {
                return $this->traverse($resource[$segment], $path);
            } else {
                return false;
            }
        }

        return $resource;
    }

} 