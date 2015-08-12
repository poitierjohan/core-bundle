<?php
// Dywee/CoreBundle/Form/SeoType.php
namespace Dywee\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('metaTitle',          'textarea', array('required' => false))
        ->add('metaDescription',    'textarea', array('required' => false))
        ->add('metaKeywords',       'textarea', array('required' => false))
        ->add('seoUrl',             'text',     array('required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true
        ));
    }

    public function getName()
    {
        return 'location';
    }
}