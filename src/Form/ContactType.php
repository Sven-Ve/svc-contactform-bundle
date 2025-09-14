<?php

/*
 * This file is part of the svc/contactform-bundle.
 *
 * (c) 2025 Sven Vetter <dev@sv-systems.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Svc\ContactformBundle\Form;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'attr' => [
                    'autofocus' => true,
                    'required' => true,
                    'aria-label' => 'Contact form subject',
                    'aria-describedby' => 'subject-help',
                    'maxlength' => 200,
                ],
                'required' => true,
                'help' => 'Please enter a brief subject for your message (5-200 characters)',
                'help_attr' => ['id' => 'subject-help'],
                'constraints' => [
                    new NotBlank(['message' => 'Subject cannot be empty']),
                    new Length([
                        'min' => 5,
                        'max' => 200,
                        'minMessage' => 'Subject must be at least {{ limit }} characters long',
                        'maxMessage' => 'Subject cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Your message',
                'attr' => [
                    'rows' => 6,
                    'required' => true,
                    'aria-label' => 'Your message content',
                    'aria-describedby' => 'message-help',
                    'maxlength' => 2000,
                ],
                'required' => true,
                'help' => 'Please enter your detailed message (10-2000 characters)',
                'help_attr' => ['id' => 'message-help'],
                'constraints' => [
                    new NotBlank(['message' => 'Message cannot be empty']),
                    new Length([
                        'min' => 10,
                        'max' => 2000,
                        'minMessage' => 'Message must be at least {{ limit }} characters long',
                        'maxMessage' => 'Message cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Your name',
                'attr' => [
                    'placeholder' => 'Firstname Lastname',
                    'required' => true,
                    'aria-label' => 'Your full name',
                    'aria-describedby' => 'name-help',
                    'maxlength' => 70,
                ],
                'required' => true,
                'help' => 'Please enter your full name (5-70 characters)',
                'help_attr' => ['id' => 'name-help'],
                'constraints' => [
                    new NotBlank(['message' => 'Name cannot be empty']),
                    new Length([
                        'min' => 5,
                        'max' => 70,
                        'minMessage' => 'Name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Name cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Your mail',
                'attr' => [
                    'required' => true,
                    'aria-label' => 'Your email address',
                    'aria-describedby' => 'email-help',
                    'type' => 'email',
                ],
                'required' => true,
                'help' => 'Please enter a valid email address for replies',
                'help_attr' => ['id' => 'email-help'],
                'constraints' => [
                    new NotBlank(['message' => 'Email cannot be empty']),
                    new Email(['message' => 'Please provide a valid email address']),
                ],
            ])
        ;

        if ($options['copyToMe']) {
            $builder->add('copyToMe', CheckboxType::class, [
                'help' => 'If checked, send a copy of this request to me',
                'required' => false,
                'attr' => [
                    'aria-label' => 'Send a copy of this message to my email',
                    'aria-describedby' => 'copy-help',
                ],
                'help_attr' => ['id' => 'copy-help'],
            ]);
        }

        if ($options['enableCaptcha']) {
            $builder->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage',
                'attr' => [
                    'aria-label' => 'Please complete the CAPTCHA verification',
                ],
            ]);
        }

        $builder
            ->add('Send', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-primary btn-block',
                    'aria-label' => 'Send contact form message',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'enableCaptcha' => null,
            'copyToMe' => null,
            'translation_domain' => 'ContactformBundle',
        ]);
    }
}
