<?php
declare(strict_types=1);

namespace App\Form;


use App\Model\Form\ReportError;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReportErrorType
 * @package App\Form
 */
final class ReportErrorType extends AbstractType
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
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('permalink', TextType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.permalink',
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('message', TextareaType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.message',
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('lastName', TextType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.last-name',
                'attr'      => ['autocomplete'=> 'family-name' ]
            ])
            ->add('firstName', TextType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.first-name',
                'attr'      => ['autocomplete'=> 'prenom' ]
            ])
            ->add('email', EmailType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.email',
                'attr'      => ['autocomplete'=> 'email' ],
                'label_attr' => ['compl' => 'modal.email-example']
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReportError::class,
            'honeypot' => true,
            'honeypot_field' => 'email_address',
            'honeypot_use_class' => false,
            'honeypot_hide_class' => 'hidden',
            'honeypot_message' => 'Champs invalides',
        ]);
    }
}
