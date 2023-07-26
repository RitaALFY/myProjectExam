<?php

namespace App\Form;

use App\Entity\Listing;
use App\Entity\Model;
use App\Repository\ModelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'form.listing.name.label',
            ])
            ->add('description', null, [
                'label' => 'form.listing.description.label',
            ])
            ->add('producedYear', DateType::class, [
                'label' => 'form.listing.producedYear.label',
                'widget' => 'single_text',
            ])
            ->add('mileage', null, [
                'label' => 'form.listing.mileage.label',
            ])
            ->add('price', null, [
                'label' => 'form.listing.price.label',
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'form.listing.createdAt.label',
                'widget' => 'single_text',
            ])
            ->add('image')

            ->add('model', EntityType::class, [
                'label' => 'form.listing.model.label',
                'required' => false,
                'class' => Model::class,
                'choice_label' => 'name',
                'query_builder' => function (ModelRepository $mr) {
                    return $mr->createQueryBuilder('m');
                }
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            $form = $event->getForm();

            $field = $form->get('image');
            // $fieldLink = $form->get('imageLink'); // Assuming this field is not part of the form anymore.

            if ($field->getData() === null) {
                $form->addError(new FormError('MET AU MOINS UNE IMAGE'));
            }
        });
    }
}
