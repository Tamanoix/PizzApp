<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HistoryController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(private EntityManagerInterface $entityManager, private Security $security )
    {

    }

    #[Route('/history', name: 'app_history')]
    public function index(): Response
    {

        $user = $this->security->getUser();

        $posId = $user->getPointOfSale()->getId();

        $orders = $this->entityManager->getRepository(Order::class)->findBy(
                [  'pointOfSale' => $posId,
                    'status'=> 3
                ]
            );
        return $this->render('history/index.html.twig', [
            'orders' => $orders,
        ]);
    }
    /**
     * @param $id
     * @return Response
     */
    #[Route('/order_history/{id}', name: 'order_details_history')]
    public function details($id): Response
    {

        $ordersDetails = $this->entityManager->getRepository(OrderDetail::class)->findBy(
            ['command' => $id,
            ]
        );
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(
            ['id' => $id,
            ]
        );


        return $this->render('history/order_details_history.html.twig', [
            'order' => $order,
            'ordersDetails' => $ordersDetails
        ]);
    }

}
