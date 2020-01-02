<?php
declare(strict_types=1);


namespace App\Form;

use App\Model\Form\SuggestByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SuggestByMailType
 * @package App\Form
 */
final class SuggestByMailType extends AbstractType
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
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('author', TextareaType::class,[
                'required'  => false,
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('documentType', ChoiceType::class,[
                'required'  => false,
                'multiple'  => false,
                'choices'   => SuggestByMail::DOCUMENT_TYPE,
                'attr'      => ['autocomplete'=> 'off' ]
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
                'required'  => false,
                'attr'      => ['autocomplete'=> 'off' ]
            ])
            ->add('email', EmailType::class,[
                'required'  => false,
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
            'data_class' => SuggestByMail::class,
            'honeypot' => true,
            'honeypot_field' => 'email_address',
            'honeypot_use_class' => false,
            'honeypot_hide_class' => 'hidden',
            'honeypot_message' => 'Champs invalides',
        ]);
    }
}
