<?php
declare(strict_types=1);

namespace App\Form\Type;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class EmailType
 * @package App\Form\Type
 */
class EmailType extends \Symfony\Component\Form\Extension\Core\Type\EmailType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = array_merge($options, [
            'attr' => ['placeholder' => 'nom@example.com'],
            'constraints' => [new Asset\Email()]
        ]);

//        $builder->
//        dump($options);
        parent::buildForm($builder, $options);
    }
}
