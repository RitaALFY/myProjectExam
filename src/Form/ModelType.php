<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Model;
use App\Repository\BrandRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'form.model.name.label',
            ])

            ->add('brand', EntityType::class, [
                'label' => 'form.model.brand.label',
                'required' => false,
                'class' => Brand::class,
                'choice_label' => 'name',
                'query_builder' => function (BrandRepository $br) {
                    return $br->createQueryBuilder('b');
                }
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
        ]);
    }
}
