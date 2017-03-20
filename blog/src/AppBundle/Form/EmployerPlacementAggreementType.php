<?php
// src/AppBundle/Form/EmployerPlacementAggreement.php
namespace AppBundle\Form;
use AppBundle\Entity\EmployerPlacementAggreement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EmployerPlacementAggreementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('location', FileType::class, array('label' => 'File (PDF or Word document)'))
            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EmployerPlacementAggreement::class, 
        ));
    }
}
