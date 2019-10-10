<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 08/10/19
 * Time: 12:02
 */

namespace App\Form;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ReportErrorPageType extends AbstractType
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
                'label'     => 'modal.report.field.object'
            ])
            ->add('message', TextareaType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.message'
            ])
            ->add('email', EmailType::class,[
                'required'  => false,
                'label'     => 'modal.report.field.email'
            ])
        ;
    }
}
