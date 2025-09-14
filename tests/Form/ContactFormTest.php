<?php

/*
 * This file is part of the svc/contactform-bundle.
 *
 * (c) 2025 Sven Vetter <dev@sv-systems.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Svc\ContactformBundle\Tests\Form;

use Svc\ContactformBundle\Form\ContactType;
use Symfony\Component\Form\Test\TypeTestCase;

class ContactFormTest extends TypeTestCase
{
    public function testFormIsSubmittedSuccessfully()
    {
        $form = $this->factory->create(ContactType::class, null);

        $formData = [
            'email' => 'test@test.com',
            'text' => 'This is only a test.',
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame('test@test.com', $form->getData()['email']);
        $this->assertSame('This is only a test.', $form->getData()['text']);
        $this->assertNull($form->getData()['subject']);
    }

    public function testFormCopyToMe()
    {
        $form = $this->factory->create(ContactType::class, null, ['copyToMe' => true]);

        $formData = [
            'copyToMe' => true,
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->getData()['copyToMe']);
    }
}
