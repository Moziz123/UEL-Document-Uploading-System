<?php
// src/AppBundle/Form/StudentType.php
namespace AppBundle\Form;

use AppBundle\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('course', ChoiceType::class, array(
            'choices' => array(
                 'Computing for Business' => 'Computing for Business',
                 'Computer Science' => 'Computer Science')
            ))
            ->add('school', ChoiceType::class, array(
            'choices' => array(
                 'Architecture, Computing and Engineering (ACE)' => 'Architecture, Computing and Engineering (ACE)')
                 
            ))
            ->add('username', TextType::class) 
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('address', TextType::class)
            ->add('city', TextType::class) 
            ->add('postcode', TextType::class)
            ->add('phoneNo', IntegerType::class)
            ->add('mobileNo', IntegerType::class)
            ->add('email', EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Student::class,
        ));
    }
}
