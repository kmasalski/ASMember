<?php

namespace Mcc\ASMemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IpRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('IpRange')
            ->add('dateCheck')
            ->add('asid')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mcc\ASMemberBundle\Entity\IpRange'
        ));
    }

    public function getName()
    {
        return 'mcc_asmemberbundle_iprangetype';
    }
}
