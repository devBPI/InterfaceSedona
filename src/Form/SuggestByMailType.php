<?php
declare(strict_types=1);


namespace App\Form;

use App\Form\Type\EmailType;
use App\Model\From\SuggestByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SuggestByMailType
 * @package App\Form
 */
class SuggestByMailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
                'attr'      => ['autocomplete'=> 'name' ]
            ])
            ->add('editor', TextType::class,[
                'required'  => false
            ])
            ->add('email', EmailType::class,[
                'required'  => false
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
