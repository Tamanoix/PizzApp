<?php

namespace App\Controller;

use App\Classe\ShortestPathAlgorithm;
use App\Entity\Circuit;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DelivererController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, private Security $security)
    {

    }

    #[Route('/deliverer', name: 'app_deliverer')]
    public function index(): Response
    {

        // get data of current manager
        $user = $this->security->getUser();

        // get id of manager point of sale
        $posId = $user->getPointOfSale()->getId();

        // array of delivers filtered by role = deliverer
        $delivers = $this->entityManager->getRepository(User::class)->findByRole('ROLE_DELIVERER');

        // $deliverers filtered by point of sale
        $managerDeliverers = [];

        foreach($delivers as $key => $value)
        {

            // status == 1 free
            $status= $value->getStatus();
            if($value->getPointOfSale()->getId() ==  $posId && $status == 1)
            {

                $managerDeliverers[$key] = [
                    'first' => $value->getFirstname(),
                    'last' => $value->getLastname(),
                    'email'=> $value->getEmail(),
                    'phonenumber'=> $value->getPhonenumber(),
                    'idDeliverer' =>$value->getId(),
                ];
            }
        }


        return $this->render('deliverer/index.html.twig', [
            "deliverers"=> $managerDeliverers

        ]);
    }


    #[Route('/deliverer/assign_circuit/{id}', name: 'app_assign_circuit')]
    public function AssignCircuitToDeliverer($id): Response
    {

        $algo = new ShortestPathAlgorithm( $this->entityManager, $this->security);

        $algo = $algo->algoShortestPath($id);

        //dd($algo, $id);

        // get deliverer
        $id_deliverer = $id;
        $deliverer = $this->entityManager->getRepository(User::class)->findOneBy(
            ['id' => $id_deliverer
            ]
        );

        // change status of deliverer with a circuit
        $deliverer->setStatus(2);
        $this->entityManager->persist($deliverer);


        $circuit = new Circuit;
        $circuit->setDeliveryman($deliverer);
        $circuit->setCoords($algo);
        $circuit->setStatus(false); // 0

        $this->entityManager->persist($circuit);

        $this->entityManager->flush();


        return $this->redirectToRoute('app_deliverer');
    }




}
