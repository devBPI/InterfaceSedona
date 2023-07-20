<?php
declare(strict_types=1);

namespace App\Form;


use App\Model\Form\ExportNotice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExportNoticeType
 * @package App\Form
 */
final class ExportNoticeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
             ->add('shortFormat', ChoiceType::class, [
                 'required' => true,
                 'label'    => false,
                 'expanded' => true,
                 'choices'  => [
                     'modal.export.field.abstract' => true,
                     'modal.export.field.long' => false
                 ],
                 'data' => true,
                 'attr'      => ['autocomplete' => "off"]
             ])
             ->add('image', CheckboxType::class,[
                 'required' => false,
                 'label'    => 'modal.export.field.img',
                 'attr'      => ['autocomplete' => "off"]
             ])
             ->add('notices', HiddenType::class,[
                 'required' => false,
                 'attr'     => [
                     'class' => 'js-print-notices'
                 ]
             ])
             ->add('authorities', HiddenType::class,[
                 'required' => false,
                 'attr'     => [
                     'class' => 'js-print-authorities'
                 ]
             ])
             ->add('indices', HiddenType::class,[
                 'required' => false,
                 'attr'     => [
                     'class' => 'js-print-indices'
                 ]
             ])
             ->add('reciever', EmailType::class, [
                'required'  => true,
                'label'     => 'modal.share.field.recipient',
                'attr'      => ['autocomplete' => "off"],
                'label_attr'=> ['compl' => 'modal.email-example']
            ])
             ->add('sender', EmailType::class, [
                 'required'  => true,
                 'label'     => 'modal.share.field.expeditor',
                 'attr'      => ['autocomplete' => "off"],
                 'label_attr'=> ['compl' => 'modal.email-example']
             ])
             ->add('object', TextType::class,[
                 'required'  => false,
                 'label'     => 'modal.export.field.object',
                 'attr'      => ['autocomplete'=> 'off' ]
             ])
             ->add('message', TextareaType::class,[
                'required'  => false,
                'label'     => 'modal.export.field.comments',
                 'attr'      => ['autocomplete'=> 'off' ]
            ])
             ->add('formatType', ChoiceType::class, [
                'required' => true,
                'label'    => false,
                'expanded' => true,
                'data'     => 'txt',
                'choices'  => [
                    'modal.export.field.txt' => 'txt',
                    'modal.export.field.pdf' => 'pdf'
                ],
                 'attr'      => ['autocomplete'=> 'off' ]
             ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExportNotice::class,
            'csrf_protection' => false,
        ]);
    }
}
