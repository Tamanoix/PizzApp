<?php

namespace App\Form;

use App\Entity\OrderDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, ['label' => 'Quantité',
        'required' => true,
        'attr' => [
            'class' => 'form-control',
        ],
        'constraints' => [
            new Assert\Positive,
            new Assert\LessThan(30),
            new Assert\NotBlank()
        ]        ])
        ->add('product'
        )

            ->add('submit', SubmitType::class, [
                'label' => "Soumettre"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderDetail::class,
        ]);
    }
}
