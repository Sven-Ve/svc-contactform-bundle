<?php


namespace Svc\ContactformBundle\Tests\Form;

use Svc\ContactformBundle\Form\ContactType;
use Symfony\Component\Form\Test\TypeTestCase;

class ContactFormTest extends TypeTestCase
{
  /**
   * @test
   */
  public function testFormIsSubmittedSuccessfully()
  {

    $form = $this->factory->create(ContactType::class, null);

    $formData = array(
      'email' => 'test@test.com',
      'text' => 'This is only a test.',
    );
    $form->submit($formData);

    $this->assertTrue($form->isSynchronized());
    $this->assertSame('test@test.com', $form->getData()['email']);
    $this->assertSame('This is only a test.', $form->getData()['text']);
    $this->assertNull($form->getData()['subject']);
  }

  /**
   * @test
   */
  public function testFormCopyToMe()
  {

    $form = $this->factory->create(ContactType::class, null, ['copyToMe' => true]);

    $formData = array(
      'copyToMe' => true,
    );
    $form->submit($formData);

    $this->assertTrue($form->isSynchronized());
    $this->assertTrue($form->getData()['copyToMe']);
  }
}
