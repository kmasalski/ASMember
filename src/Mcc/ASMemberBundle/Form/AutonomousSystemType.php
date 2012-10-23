<?php

namespace Mcc\ASMemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutonomousSystemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asidentifier')
            ->add('asname')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mcc\ASMemberBundle\Entity\AutonomousSystem'
        ));
    }

    public function getName()
    {
        return 'mcc_asmemberbundle_autonomoussystemtype';
    }
}
