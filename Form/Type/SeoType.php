<?php
// Dywee/CoreBundle/Form/SeoType.php
namespace Dywee\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('metaTitle',          null,     array('required' => false))
        ->add('metaDescription',    TextareaType::class, array('required' => false))
        ->add('metaKeywords',       TextareaType::class, array('required' => false))
        ->add('seoUrl',             null,     array('required' => false));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $entity = $event->getData();
            $form = $event->getForm();

            // Dans le cas où c'est la page d'accueil
            if ($entity instanceof Dywee\DyweeCMSBundle\Entity\Page){
                if($entity->getType() == 1)
                    $form->remove('seoUrl');
            }
        });
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