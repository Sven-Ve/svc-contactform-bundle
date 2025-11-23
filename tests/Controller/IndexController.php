<?php

declare(strict_types=1);

/*
 * This file is part of the svc/contactform-bundle.
 *
 * (c) 2025 Sven Vetter <dev@sv-systems.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Svc\ContactformBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simple index controller for testing redirect after form submission.
 */
class IndexController extends AbstractController
{
    public function index(): Response
    {
        return new Response('Index page');
    }
}
