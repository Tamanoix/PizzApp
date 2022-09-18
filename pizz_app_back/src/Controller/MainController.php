<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\AddProductType;
use App\Form\AffectOrderType;
use App\Form\OrderType;
use App\Form\UpdateOrderStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{


    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(private EntityManagerInterface $entityManager, private Security $security )
    {

    }

    /**
     * @return Response
     */
    #[Route('/order', name: 'app_main')]
    public function index(): Response
    {
       $user = $this->security->getUser();

       $roles  = $user->getRoles();


       if($roles[0] === "ROLE_ADMIN")
       {
           return $this->redirectToRoute('admin');
       }else{
           $posId = $user->getPointOfSale()->getId();

           $orders = $this->entityManager->getRepository(Order::class)->findBy(
               ['deliverer' => null,
                'pointOfSale' => $posId,
                'status'=> 0
               ]
           );

           //dd($orders);
           return $this->render('main/index.html.twig', [
               'orders' => $orders
           ]);
       }


    }


    /**
     * @param $id
     * @return Response
     */
    #[Route('/order/{id}', name: 'order_details')]
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


        return $this->render('main/order.html.twig', [
            'order' => $order,
            'ordersDetails' => $ordersDetails
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    #[Route('/order/add_product_in_order/{id}',  name:'app_main_add_product_in_order', methods: ['GET', 'POST'])]
    public function addProductInOrder(

        Request $request,
        $id
    ): Response
    {

        $orderdetail = new OrderDetail();
        $form =$this->createForm(AddProductType::class, $orderdetail);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $command_id = $this->entityManager->getRepository(OrderDetail::class)->findBy(['command'=> $id]);
            $command_id = $command_id[0]->getCommand();
            //dd($command_id);
            $orderdetail = $form->getData();
            $orderdetail->setCommand($command_id);
            $this->entityManager->persist($orderdetail);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a été pris en compte'
            );
            return $this->redirectToRoute('order_details', [
                'id' => $id
            ]);
        //return $this->redirectToRoute('order_details', ['id' => $id]);
        }


        return $this->render('main/add_product_in_order.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @param $id
     * @return Response
     */
    #[Route('/order/delete_row_of_order_detail/{id}',  name:'app_main_delete_row_of_order_detail', methods: ['GET', 'DELETE'])]
    public function deleteRowOfOrder(
                $id
    ): Response
    {

        $orderdetail = $this->entityManager->getRepository(OrderDetail::class)->find($id);
        //dd($orderdetail[0]);

        $order = $orderdetail->getCommand();

        $this->entityManager->remove($orderdetail);
        $this->entityManager->flush();

        //return $this->redirectToRoute('app_main');/**/
         return $this->redirectToRoute('order_details', [
                'id' => $order->getId()
            ]);

    }


    /**
     * @param Order $order
     * @param Request $request
     * @return Response
     */
    #[Route('/order/affect_order/{id}',  name:'app_main_affect_order', methods: ['GET', 'POST'])]
    public function affect_order(
        Order $order,
        Request $request,
    ): Response
    {
        //dd($Order);

        $form =$this->createForm(AffectOrderType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $order = $form->getData();
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a été pris en compte'
            );
            return $this->redirectToRoute('app_main');
        }


        return $this->render('main/affect_order.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @param Order $order
     * @param Request $request
     * @return Response
     */
    #[Route('/order/edition/{id}',  name:'app_main_edit_order', methods: ['GET', 'POST'])]
    public function edit_order(
        Order $order,
        Request $request,
        ): Response
    {
        //dd($Order);

        $form =$this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $order = $form->getData();
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a été pris en compte'
            );
            return $this->redirectToRoute('app_main');
        }


        return $this->render('main/edit_order.html.twig', [
            'form' => $form->createView()
         ]);
    }

   /* #[Route('/home/delete/{id}', name:'app_main_delete_by_id', methods:['GET','DELETE'])]
    public function delete_order($id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_main');
       // return $this->render('main/order.html.twig', [
       //  ]);
    }*/


    /**
     * @param Order $order
     * @param Request $request
     * @return Response
     */
    #[Route('/order/delete_order/{id}', name:'app_main_delete_by_id', methods:['GET','POST'])]
    public function delete_order( Order $order,
                                  Request $request,): Response
    {

        $form =$this->createForm(UpdateOrderStatusType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $order = $form->getData();
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a été pris en compte'
            );
            return $this->redirectToRoute('app_main');
        }


        return $this->render('main/delete.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
