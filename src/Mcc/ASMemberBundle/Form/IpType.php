<?php

namespace Mcc\ASMemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ip')
            ->add('hostname')
            ->add('iswebserver')
            ->add('lastcheck')
            ->add('asidentifier')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mcc\ASMemberBundle\Entity\Ip'
        ));
    }

    public function getName()
    {
        return 'mcc_asmemberbundle_iptype';
    }
}
