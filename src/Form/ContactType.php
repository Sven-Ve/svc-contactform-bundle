<?php

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

class ContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
        ->add('subject', TextType::class, ['label' => 'Subject', 'attr' => ['autofocus' => true]])
        ->add('text', TextareaType::class, ['label' => 'Your message', 'attr' => ['rows' => 6]])
        ->add('name', TextType::class, [
          'label' => 'Your name',
          'attr' => ['placeholder' => 'Firstname Lastname'],
        ])
        ->add('email', EmailType::class, ['label' => 'Your mail'])
      ;

    if ($options['copyToMe']) {
      $builder->add('copyToMe', CheckboxType::class, [
        'help' => 'If checked, send a copy of this request to me',
        'required' => false,
      ]);
    }

    if ($options['enableCaptcha']) {
      $builder->add('captcha', Recaptcha3Type::class, [
        'constraints' => new Recaptcha3(),
        'action_name' => 'homepage',
      ]);
    }

    $builder
        ->add('Send', SubmitType::class, ['attr' => ['class' => 'btn btn-lg btn-primary btn-block']])
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
