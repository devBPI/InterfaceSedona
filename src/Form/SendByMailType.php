<?php
declare(strict_types=1);

namespace App\Form;

use App\Model\Form\ExportNotice;
use App\Model\Form\SendByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SendByMailType
 * @package App\Form
 */
final class SendByMailType extends ExportNoticeType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('formatType', HiddenType::class, [
                'data'     => ExportNotice::FORMAT_EMAIL,
            ])
            ->add('sender', EmailType::class, [
                'required' => false,
                'label'     => 'modal.share.field.expeditor',
                'attr'      => ['autocomplete' => "off"],
                'label_attr'=> ['compl' => 'modal.email-example']
            ])
            ->add('reciever', EmailType::class, [
                'required'  => true,
                'label'     => 'modal.share.field.recipient',
                'attr'      => ['autocomplete' => "off"],
                'label_attr'=> ['compl' => 'modal.email-example']
            ])
            ->add('message', TextareaType::class,[
                'required'  => false,
                'label'     => 'modal.export.field.comments',
                'attr'      => ['autocomplete'=> 'off' ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SendByMail::class,
            'honeypot' => true,
            'honeypot_field' => 'email_address',
            'honeypot_use_class' => false,
            'honeypot_hide_class' => 'hidden',
            'honeypot_message' => 'Champs invalides',
        ]);
    }
}
