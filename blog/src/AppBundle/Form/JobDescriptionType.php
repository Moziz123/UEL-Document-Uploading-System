<?php
// src/AppBundle/Form/JobDescriptionType.php
namespace AppBundle\Form;
use AppBundle\Entity\JobDescription;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class JobDescriptionType extends AbstractType
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
            'data_class' => JobDescription::class, 
        ));
    }
}
