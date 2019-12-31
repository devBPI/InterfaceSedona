<?php
declare(strict_types=1);


namespace App\Form;

use App\Model\Form\ShareByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ShareByMailType
 * @package App\Form
 */
final class ShareByMailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('object', HiddenType::class,[
                'label'     => 'modal.share.field.title',
                'data'      => 'modal.share.field.title_value'
            ])
            ->add('message', TextareaType::class,[
                'required'  => true,
                'label'     => 'modal.share.field.text',
            ])
            ->add('sender', EmailType::class,[
                'required'  => true,
                'label'     => 'modal.share.field.expeditor',
                'label_attr' => ['compl' => 'modal.email-example']
            ])
            ->add('reciever', EmailType::class,[
                'required'  => true,
                'label'     => 'modal.share.field.recipient',
                'label_attr' => ['compl' => 'modal.email-example']
            ])
            ->add('link', HiddenType::class,[
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
            'honeypot' => true,
            'honeypot_field' => 'email_address',
            'honeypot_use_class' => false,
            'honeypot_hide_class' => 'hidden',
            'honeypot_message' => 'Champs invalides',
        ]);
    }
}
