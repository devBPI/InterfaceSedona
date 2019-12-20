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
                 'required'=> true,
                 'label'=> false,
                 'expanded'=>true,
                 'choices'=>[
                     'Notices abrégées'=>true,
                     'Notices complete'=>false,
                 ],
                 'data' => true
             ])
             ->add('image', CheckboxType::class,[
                 'required' => false,
                 'label' => 'modal.export.field.img'
             ])
             ->add('notices', HiddenType::class,[
                 'required' => false,
                 'attr'=>[
                     'class' => 'js-print-notices'
                 ]
             ])
             ->add('authorities', HiddenType::class,[
                 'required' => false,
                 'attr'=>[
                     'class' => 'js-print-authorities'
                 ]
             ])
             ->add('indices', HiddenType::class,[
                 'required' => false,
                 'attr'=>[
                     'class' => 'js-print-indices'
                 ]
             ])
             ->add('reciever', EmailType::class, [
                    'required'      => true,
                    'label'         =>'modal.share.field.recipient',
                    'attr'=>[
                        'autocomplete'  =>"off",
                        ]
                 ])
             ->add('message', TextareaType::class,[
                    'required' => false,
                    'label' => 'modal.export.field.comments'
                 ])
             ->add('formatType', ChoiceType::class, [
                     'required' => true,
                     'label'=> false,
                     'expanded'=>true,
                     'choices' => [
                         'Format texte brute'   =>'txt',
                         'Mettre PDF en pièce jointe'           =>'pdf',
                     ],
                    'data' => 'txt'
                 ])
             ;
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
