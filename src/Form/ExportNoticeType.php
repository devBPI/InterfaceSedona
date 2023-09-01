<?php
declare(strict_types=1);

namespace App\Form;


use App\Kernel;
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
class ExportNoticeType extends AbstractType
{
    /**
     * @var string
     */
    protected  $env;

    public function __construct(string $env)
    {
        $this->env = $env;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
             ->add('formatType', ChoiceType::class, [
                'required' => true,
                'label'    => false,
                'expanded' => true,
                'data'     => ExportNotice::FORMAT_TEXT,
                'choices'  => [
                    'modal.export.field.txt' => ExportNotice::FORMAT_TEXT,
                    'modal.export.field.pdf' => ExportNotice::FORMAT_PDF
                ],
                 'attr'      => ['autocomplete'=> 'off' ]
             ])
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
             ->add('notices', $this->getTypeOfHidden(),[
                 'required' => false,
                 'attr'     => [
                     'class' => 'js-print-notices'
                 ]
             ])
             ->add('authorities', $this->getTypeOfHidden() ,[
                 'required' => false,
                 'attr'     => [
                     'class' => 'js-print-authorities'
                 ]
             ])
             ->add('indices', $this->getTypeOfHidden(),[
                 'required' => false,
                 'attr'     => [
                     'class' => 'js-print-indices'
                 ]
             ])
         ;
    }

    protected function getTypeOfHidden() :string
    {
        return $this->env == 'test' ? TextType::class : HiddenType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExportNotice::class,
            'csrf_protection' => false,
        ]);
    }
}
