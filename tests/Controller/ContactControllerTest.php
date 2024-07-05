<?php

declare(strict_types=1);

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
