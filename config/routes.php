<?php

use Svc\ContactformBundle\Controller\ContactController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('svc_contact_form', '/contact/')
        ->controller([ContactController::class, 'contactForm']);
};