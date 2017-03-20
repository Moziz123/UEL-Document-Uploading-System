<?php
// src/AppBundle/Form/CompanyType.php
namespace AppBundle\Form;
use AppBundle\Entity\Company;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('address', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('city', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('postcode', TextType::class, array('attr' => array(
            'class' => 'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Company::class, 
        ));
    }
}
