<?php
// src/AppBundle/Form/LinemanagerType.php
namespace AppBundle\Form;
use AppBundle\Entity\LineManager;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LinemanagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('lastname', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('position', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('phoneNo', IntegerType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('email', EmailType::class, array('attr' => array(
            'class' => 'form-control'))) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => LineManager::class, 
        ));
    }
}
