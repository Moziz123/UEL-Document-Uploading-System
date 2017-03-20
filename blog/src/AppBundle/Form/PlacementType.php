<?php
// src/AppBundle/Form/PlacementType.php
namespace AppBundle\Form;

use AppBundle\Entity\Placement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class PlacementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', CompanyType::class)
            ->add('startDate', DateTimeType::class)
            ->add('endDate', DateTimeType::class) 
            ->add('jobTitle', TextType::class)
            ->add('hoursPerWeek', TextType::class)

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
            'data_class' => Placement::class,
        ));
    }
}
