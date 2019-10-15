<?php
declare(strict_types=1);

namespace App\Form;


use App\Form\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ReportErrorType
 * @package App\Form
 */
class ReportErrorType extends AbstractType
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
                'label'     => 'modal.report.field.object'
            ])
            ->add('message', TextareaType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.message',
                'constraints'=> [ new Asset\NotBlank() ]
            ])
            ->add('lastName', TextType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.last-name',
                'attr'      => ['autocomplete'=> 'family-name' ]
            ])
            ->add('firstName', TextType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.first-name',
                'attr'      => ['autocomplete'=> 'name' ]
            ])
            ->add('email', EmailType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.email',
                'attr'      => ['autocomplete'=> 'email' ]
            ])
        ;
    }
//
//    /**
//     * @param OptionsResolver $resolver
//     */
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => ReportError::class,
//        ]);
//    }
}
