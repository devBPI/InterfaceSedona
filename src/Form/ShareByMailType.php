<?php


namespace App\Form;

use App\Model\From\ShareByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Asset;

class ShareByMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('object', TextType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.object',
            ])
            ->add('message', TextareaType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.message',

            ])
            ->add('sender', TextType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.last-name',
                'attr'      => ['autocomplete'=> 'family-name' ],
                'constraints'=> [ new Asset\Email()  ]

            ])
            ->add('reciever', TextType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.first-name',
                'attr'      => ['autocomplete'=> 'name' ],
                'constraints'=> [ new Asset\Email()  ]

            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShareByMail::class,
            'csrf_protection' => false,
        ]);
    }
}
