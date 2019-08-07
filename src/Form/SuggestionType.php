<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Asset;

class SuggestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('object', TextType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.object',
                'constraints'=> [ new Asset\NotBlank() ]
            ])
            ->add('message', TextareaType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.message'
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
                'constraints'=> [ new Asset\Email() ]
            ])
        ;
    }
}
