<?php

namespace App\ServiceClass;

class Tsp {

    private $locations     = array();        // all locations to visit
    private $longitudes = array();
    private $latitudes     = array();
    private $shortest_route = array();    // holds the shortest route
    private $shortest_routes = array();    // any matching shortest routes
    private $shortest_distance = 0;        // holds the shortest distance
    private $all_routes = array();        // array of all the possible combinations and there distances

    private const LAT_1 = null;
    private const LAT_2 = null;

// add a location
    public function add($name,$longitude,$latitude){
        $this->locations[$name] = array('longitude'=>$longitude,'latitude'=>$latitude);

        return $this->locations;
    }

//LAT    LON     Location  - added method for parameter order
    public function _add($latitude,$longitude,$name){
        $this->locations[$name] = array('longitude'=>$longitude,'latitude'=>$latitude);
    }

// the main function that des the calculations
    public function compute(){
        $locations = $this->locations;

        foreach ($locations as $location=>$coords){
            $this->longitudes[$location] = $coords['longitude'];
            $this->latitudes[$location] = $coords['latitude'];
        }
        $locations = array_keys($locations);

        $this->all_routes = $this->array_permutations($locations);

        $cache = array();
        foreach ($this->all_routes as $key=>$perms){
            $i=0;
            $total = 0;
            $n = count($this->locations)-1;
            foreach ($perms as $value){
                if ($i<$n){
                    $source = $perms[$i];
                    $dest = $perms[$i+1];
                    if(isset($cache[$source][$dest])){
                        $dist = $cache[$source][$dest];
                    } elseif (isset($cache[$dest][$source])) {
                        $dist = $cache[$dest][$source];
                    } else {
                        $dist = $this->distance($this->latitudes[$source],$this->longitudes[$source],$this->latitudes[$dest],$this->longitudes[$dest]);
                        $cache[$source][$dest] = $dist;
                    }
                    $total+=$dist;
                }
                $i++;
            }
            $this->all_routes[$key]['distance'] = $total;
            if ($total<$this->shortest_distance || $this->shortest_distance ==0){
                $this->shortest_distance = $total;
                $this->shortest_route = $perms;
                $this->shortest_routes = array();
            }
            if ($total == $this->shortest_distance){
                $this->shortest_routes[] = $perms;
            }
        }
    }

// work out the distance between 2 longitude and latitude pairs
    function distance($lat1, $lon1, $lat2, $lon2) {
        if ($lat1 == $lat2 && $lon1 == $lon2) return 0;
        $theta = $lon1 - $lon2;
        $r_l1 = deg2rad($lat1);
        $r_l2 = deg2rad($lat2);
        $dist = sin($r_l1) * sin($r_l2) +  cos($r_l1) * cos($r_l2) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 69.09;
        return $miles;
    }

//    public function shortestDistanceBetweenTwoPoints($lat1, $lon1, $arrayCoords)
//    {
//        // coordonn??es du point de vente
//        $lat1_pos = $lat1;
//        $lng1_pos = $lon1;
//
//        $arrayForSorting = $arrayCoords;
//        // Pour chaque point de vente calcul de distance
//        foreach($arrayCoords as $key => $value)
//        {
//            dd($key, $value);
//
//            $lat2_pos = $value;
//            $lng2_pos = $value;
//            // On lance la fonction de calcul de la distance
//            $distance = $this->distance($lat1_pos, $lng1_pos, $lat2_pos, $lng2_pos);
//
//
//        }
//
//
//     /*   // Si la distance qui s??pare le client du point de vente est inf??rieure ou ??gale ?? 15km, on stocke le point de vente
//        if (distance <= 15) {
//            resultsDistance.push({
//                id : el.id,
//                name : el.name,
//                dist : distance
//            });*/
//
//
//        }


// work out all the possible different permutations of an array of data
    private function array_permutations($items, $perms = array()){
        static $all_permutations;
        if (empty($items)) {
            $all_permutations[] = $perms;
        }  else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                $this->array_permutations($newitems, $newperms);
            }
        }
        return $all_permutations;
    }

// return an array of the shortest possible route
    public function shortest_route(){
        return $this->shortest_route;
    }

// returns an array of any routes that are exactly the same distance as the shortest (ie the shortest backwards normally)
    public function matching_shortest_routes(){
        return $this->shortest_routes;
    }

// the shortest possible distance to travel
    public function shortest_distance(){
        return $this->shortest_distance;
    }

// returns an array of all the possible routes
    public function routes(){
        return $this->all_routes;
    }
}
