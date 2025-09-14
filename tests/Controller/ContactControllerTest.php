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

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactControllerTest extends KernelTestCase
{
    public function testOpenContactForm()
    {
        $kernel = self::bootKernel();
        $client = new KernelBrowser($kernel);
        $client->request('GET', '/contactform/en/contact/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testContactFormContent()
    {
        $kernel = self::bootKernel();
        $client = new KernelBrowser($kernel);
        $client->request('GET', '/contactform/en/contact/');
        $this->assertStringContainsString('Contact', $client->getResponse()->getContent());
    }

    public function testContactFormContentDE()
    {
        $kernel = self::bootKernel();
        $client = new KernelBrowser($kernel);
        $client->request('GET', '/contactform/de/contact/');
        $this->assertStringContainsString('Kontakt', $client->getResponse()->getContent());
    }
}
