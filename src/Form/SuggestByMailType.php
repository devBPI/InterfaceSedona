<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 01/10/19
 * Time: 11:57
 */


namespace App\Form;

use App\Model\From\SuggestByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Asset;

class SuggestByMailType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'required'  => true,
            ])
            ->add('author', TextareaType::class,[
                'required'  => false,
            ])
            ->add('documentType', ChoiceType::class,[
                    'required'=> true,
                    'multiple' => false,
                    'choices'=> SuggestByMail::DOCUMENT_TYPE
                ])
            ->add('lastName', TextType::class,[
                'required'  => false,
                'attr'      => ['autocomplete'=> 'family-name' ]
            ])
            ->add('firstName', TextType::class,[
                'required'  => false,
            ])
            ->add('editor', TextType::class,[
                'required'  => false,
                'attr'      => ['autocomplete'=> 'name' ]
            ])
            ->add('email', EmailType::class,[
                'required'  => false,
                'constraints'=> [ new Asset\Email() ],
                'attr' => array(
                                'placeholder' => 'nom@example.com'
                                )
            ])
        ;
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SuggestByMail::class,
            'csrf_protection' => false,
        ]);
    }
}
