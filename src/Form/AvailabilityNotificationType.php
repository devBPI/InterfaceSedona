<?php
declare(strict_types=1);

namespace App\Form;


use App\Entity\NoticeAvailabilityRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AvailabilityNotificationType
 * @package App\Form
 */
final class AvailabilityNotificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'notification_email',
                EmailType::class,
                [
                    'required' => true,
                    'label' => 'modal.notifed-form.field-email',
                    'label_attr' => ['compl' => 'modal.email-example'],
                    'attr' => [
                        'autocomplete' => 'email',
                        'placeholder' => 'global.form.email.placeholder',
                    ],
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => NoticeAvailabilityRequest::class,
                'honeypot' => true,
                'honeypot_field' => 'email_address',
                'honeypot_use_class' => false,
                'honeypot_hide_class' => 'hidden',
                'honeypot_message' => 'message non localisée',
            ]
        );
    }
}
