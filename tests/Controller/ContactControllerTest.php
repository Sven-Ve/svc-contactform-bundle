<?php

declare(strict_types=1);

namespace Svc\ContactformBundle\Tests\Controller;

use Svc\ContactformBundle\Tests\SvcContactformTestingKernel;
use Svc\ContactformBundle\Tests\SvcUtilTestingKernel;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChangeMailControllerTest extends KernelTestCase
{

  public function testOpenContactForm()
  {
    $kernel = new SvcContactformTestingKernel();
    $client = new KernelBrowser($kernel);
    $client->request('GET', '/api/en/contact');
    $this->assertSame(200, $client->getResponse()->getStatusCode());
  }

  public function testContactFormContent()
  {
    $kernel = new SvcContactformTestingKernel();
    $client = new KernelBrowser($kernel);
    $client->request('GET', '/api/en/contact');
    $this->assertStringContainsString("Contact", $client->getResponse()->getContent());
  }

  public function testContactFormContentDE()
  {
    $kernel = new SvcContactformTestingKernel();
    $client = new KernelBrowser($kernel);
    $client->request('GET', '/api/de/contact');
    $this->assertStringContainsString("Kontakt", $client->getResponse()->getContent());
  }
}
