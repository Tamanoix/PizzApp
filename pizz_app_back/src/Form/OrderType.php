<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('status')
           /* ->add('deliverer', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $ur) {
                    return $ur->findByRoleQb('ROLE_DELIVERER');
                },
                //'choice_label' => 'username',
            ])*/
            ->add('orderDetails', CollectionType::class, [
            'entry_type' => OrderDetailType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Soumettre"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
