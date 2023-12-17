<?php

declare(strict_types=1);

namespace Svc\ContactformBundle\Tests\Controller;

use Svc\ContactformBundle\Tests\SvcContactformTestingKernel;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactControllerTest extends KernelTestCase
{

  public function testOpenContactForm()
  {
    $kernel = new SvcContactformTestingKernel();
    $client = new KernelBrowser($kernel);
    $client->request('GET', '/contactform/en/contact/');
    $this->assertSame(200, $client->getResponse()->getStatusCode());
  }

  public function testContactFormContent()
  {
    $kernel = new SvcContactformTestingKernel();
    $client = new KernelBrowser($kernel);
    $client->request('GET', '/contactform/en/contact/');
    $this->assertStringContainsString("Contact", $client->getResponse()->getContent());
  }

  public function testContactFormContentDE()
  {
    $kernel = new SvcContactformTestingKernel();
    $client = new KernelBrowser($kernel);
    $client->request('GET', '/contactform/de/contact/');
    $this->assertStringContainsString("Kontakt", $client->getResponse()->getContent());
  }
}
