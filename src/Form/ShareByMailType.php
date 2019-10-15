<?php
declare(strict_types=1);


namespace App\Form;

use App\Form\Type\EmailType;
use App\Model\From\ShareByMail;
use Symfony\Component\Form\AbstractType;
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
                'label'     => 'modal.report.field.object',
                'data'      => 'Références du catalogue de la Bpi'
            ])
            ->add('message', TextareaType::class,[
                'required'  => true,
                'label'     => 'modal.report.field.message',
            ])
            ->add('sender', EmailType::class,[
                'required'  => true,
                'label'     => 'modal.share.field.expeditor'
            ])
            ->add('reciever', EmailType::class,[
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
