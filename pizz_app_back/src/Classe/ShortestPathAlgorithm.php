<?php

namespace App\Classe;

use App\Entity\Order;
use App\Entity\User;
use App\ServiceClass\Tsp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


/**
 *
 */
class ShortestPathAlgorithm
{


    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(private EntityManagerInterface $entityManager, private Security $security)
    {

    }


    /**
     * @return array
     * @throws \Exception
     */
    public function getOrders() : array
    {
        // get info about current manager
        $user = $this->security->getUser();

        $posId = $user->getPointOfSale()->getId();
        $posLat = $user->getPointOfSale()->getLatitude();
        $posLng = $user->getPointOfSale()->getLongitude();

        //ASC LIMIT 4  where status = null;
        //  WHERE o.status = null
        $query = $this->entityManager->createQuery(
            'SELECT o FROM App\Entity\order o    
        WHERE o.status = 0 AND o.pointOfSale = :posId      
        ORDER BY o.id ASC    
    '
        )->setParameter('posId',   $posId );

        $orders = $query->setMaxResults(4)->getResult();

        // get coordonates of customer by status order = 0 and same manager point of sale
        $arrayOfCustomerCoord = [];
        foreach($orders as $key => $value)
        {

            $arrayOfCustomerCoord[$key] =
                [
                    'lat' => $value->getCustomer()->getLatitude(),
                    'lng' => $value->getCustomer()->getLongitude(),
                    'createdAt'=>$value->getCreatedAt(),
                    'idOrder' => $value->getId(),
                    'idCustomer' => $value->getCustomer()->getId(),
                ];

        }
        // algo attribue 4 premiers résultats au deliver qui est ok

        return [$posLat, $posLng, $arrayOfCustomerCoord];
    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function algoShortestPath($id) : array
    {
        $coord = $this->getOrders();

        $orders =  $coord[2];

        $id_deliverer = $id;
        $deliverer = $this->entityManager->getRepository(User::class)->findOneBy(
            ['id' => $id_deliverer
            ]
        );

        // algo to get the shortest route
        $s = microtime(true);

        $tsp = new Tsp;

        foreach($coord[2] as $key => $value)
        {
            $id_order = strval($value["idOrder"]);
            $tsp->_add($value["lat"],  $value["lng"],   $id_order);
        }

        $tsp->compute();

        $e = microtime(true);
        $t = $e - $s;

        $shortestRoute = $tsp->shortest_route();

        $route = [];

        // changement du statut des commandes
        if(count($route) !== 0)
        {
            //allocation of orders to deliverers
            foreach($orders  as $key => $value)
            {
                $id_order = $value["idOrder"];

                $order = $this->entityManager->getRepository(Order::class)->find($id_order);

                // set status to 1 ( = assigned command)
                $order->setStatus( 1 );
                $order->setDeliverer( $deliverer);

                $this->entityManager->persist($order);
                $this->entityManager->flush();

            }

        }


        return $shortestRoute;
    }

}
