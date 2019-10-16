<?php
declare(strict_types=1);


namespace App\Form;

use App\Model\Form\ShareByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ShareByMailType
 * @package App\Form
 */
class ShareByMailType extends AbstractType
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
                'label'     => 'modal.share.field.title',
                'data'      => 'modal.share.field.title_value'
            ])
            ->add('message', TextareaType::class,[
                'required'  => true,
                'label'     => 'modal.share.field.text',
            ])
            ->add('sender', EmailType::class,[
                'required'  => true,
                'label'     => 'modal.share.field.expeditor'
            ])
            ->add('receiver', EmailType::class,[
                'required'  => true,
                'label'     => 'modal.share.field.recipient'
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
