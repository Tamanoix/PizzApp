import React from 'react';

export default function findNearby( tabCoordsUser: any , tabPointsOfSales: any , resultsDistance: any) 
{
    // Fonction pour calculer une distance entre 2 points
    function calculDistance(lat1:any, lon1:any, lat2:any, lon2:any) 
    { 
        if ((lat1 == lat2) && (lon1 == lon2)) {
            return 0;
        }
        else 
        {
            var radlat1 = Math.PI * lat1/180;
            var radlat2 = Math.PI * lat2/180;
            var theta = lon1-lon2;
            var radtheta = Math.PI * theta/180;
            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            if (dist > 1) {
                dist = 1;
            }
            dist = Math.acos(dist);
            dist = dist * 180/Math.PI;
            dist = dist * 60 * 1.1515;
            dist = dist * 1.609344; // renvoie une distance en km
            return dist;
        }
    }

    // On fixe "en dur" les coordonnées de l'utilisateur
    const lat1 = tabCoordsUser[0].lat;
    const lon1 = tabCoordsUser[0].lon;

    // Pour chaque point de vente
    tabPointsOfSales.map((el:any) => 
    {
        // On stocke les coordonnées du point de vente
        var lat2 = el.lat;
        var lon2 = el.lon;

        // On lance la fonction de calcul de la distance
        let distance = calculDistance(lat1, lon1, lat2, lon2);

        // Si la distance qui sépare le client du point de vente est inférieure ou égale à 15km, on stocke le point de vente
        if (distance <= 15) {
            resultsDistance.push({ 
                id : el.id,  
                name : el.name, 
                dist : distance
            });
        }
        else {
            // Sinon on ne stocke rien du tout, le point de vente est trop loin
        }
    });

    return resultsDistance;

}
