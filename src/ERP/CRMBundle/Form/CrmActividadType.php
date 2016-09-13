<?php

namespace ERP\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CrmActividadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('fechaRegistro', 'datetime')
            ->add('fechaInicio', 'datetime')
            ->add('fechaFin', 'datetime')
            ->add('fechaCancelacion', 'datetime')
            ->add('tipoActividad')
            ->add('prioridad')
            ->add('estadoActividad')
            ->add('sucursal')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ERP\CRMBundle\Entity\CrmActividad'
        ));
    }
}
