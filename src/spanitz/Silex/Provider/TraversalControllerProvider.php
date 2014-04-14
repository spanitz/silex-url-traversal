<?php
namespace spanitz\Silex\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class TraversalControllerProvider implements ControllerProviderInterface
{
    protected $rootFactory;
    protected $controller;
    protected $prefix;

    public function __construct (callable $rootFactory, callable $controller, $prefix = '')
    {
        $this->rootFactory = $rootFactory;
        $this->controller = $controller;
        $this->prefix = $prefix;
    }

    public function connect (Application $app)
    {
        if (substr($this->prefix, -1) !== '/') {
            $this->prefix .= '/';
        }

        if (!isset($app['traversal.root_factory'])) {
            $app['traversal.root_factory'] = array();
        }

        if (!isset($app['traversal.controller'])) {
            $app['traversal.controller'] = array();
        }

        $app['traversal.root_factory'] = array_merge($app['traversal.root_factory'], array($this->prefix => $this->rootFactory));
        $app['traversal.controller'] = array_merge($app['traversal.controller'], array($this->prefix => $this->controller));

        $controllers = $app['controllers_factory'];

        $controllers
            ->match($this->prefix.'{url}', 'spanitz\Silex\TraversalController::dispatch')
            ->assert('url', '.*')
            ->value('prefix', $this->prefix);

        return $controllers;
    }
} 