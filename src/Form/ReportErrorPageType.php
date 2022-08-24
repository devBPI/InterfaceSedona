<?php
declare(strict_types=1);

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ReportErrorPageType
 * @package App\Form
 */
final class ReportErrorPageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('object', TextType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.object',
                'constraints' => [ new Asset\NotBlank(['message' => 'text.empty']) ],
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('message', TextareaType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.message',
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('email', EmailType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.email',
                'constraints' => [ new Asset\Email(['message'=>'email.format']) ],
                'label_attr' => ['compl' => 'modal.email-example'],
                'attr'      => ['autocomplete'=> 'off' ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'honeypot' => true,
            'honeypot_field' => 'email_address',
            'honeypot_use_class' => false,
            'honeypot_hide_class' => 'hidden',
            'honeypot_message' => 'Champs invalides',
        ]);
    }
}
