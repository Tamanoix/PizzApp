import React, { useState, useEffect } from 'react';
import { IonImg, IonItem, IonLabel, IonIcon, IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonCardSubtitle, IonButton, IonContent, IonHeader, IonPage, IonTitle, IonToolbar } from '@ionic/react';
import Pizza from "../assets/pizzApp.jpg";
import { store } from '../state/store';
import axios from 'axios';
import findNearby from '../utils/findNearby';

import './Home.css';
import { getCoordsUser, getCoordsUserWithoutPostCode, getPointOfSales } from '../utils/api';
import { sortData } from '../utils/sortFunction';


interface userInfosInterface {
  address?: string;
  zipcode?: string;
}

interface coordsUserInterface {
  features?: {}[];
}


const Home: React.FC = () => {


  const token = store.getState().user.token;
  const dataUser = store.getState().user.user;
  const userInfos: userInfosInterface = dataUser[0];

  const [pointOfSales, setPointOfSales] = useState([]);
  const [coordsUser, setCoordsUser] = useState<coordsUserInterface | any>({});

  // Récupère l'adresse et le code postal renseignés par l'utilisateur (impossible dans ce projet car base de données avec des fixtures générées aléatoirement)
  // let userAddressToFormat: any = userInfos.address; const postCode: any = userInfos.zipcode;

  // Ici donc les données de test :
  let userAddressToFormat: any = '155 Rue du Dirigeable'; const postCode = '13400';
  /* 
  let userAddressToFormat: any = '929 avenue des Paluds'; const postCode = '13400';
  let userAddressToFormat: any = '120 Av. de Saint-Roch'; const postCode = '13430';
  let userAddressToFormat: any = 'Av. François Chardigny'; const postCode: any = '13011';
  */

  // L'API Adresses du Gouvernement attend une string " 155+Rue+du+Dirigeable " donc ici on gère toutes les virgules éventuelles (on peut gérer tous les caractères spéciaux via la Regex)
  var specialChars = ",";
  for (var i = 0; i < specialChars.length; i++) {
    userAddressToFormat = userAddressToFormat.replace(new RegExp("\\" + specialChars[i], "gi"), "");
  }
  // Et ensuite on remplace les espaces par des +
  const userAddress = userAddressToFormat.replaceAll(' ', '+');
  

  useEffect(() => {

    // à l'instanciation du composant, on récupère tous nos points de vente
    const handlePointOfSales = async () => {
      try {
        const response = await getPointOfSales(token);
        setPointOfSales(response.data);
      }
      catch (err) {
        console.log(err);
      }
    };

    handlePointOfSales();

    // à l'instanciation du composant, on transforme l'adresse de l'utilisateur en longitude/latitude
    const handleCoordsUser = async () => {
      try {
        const response = await getCoordsUser(userAddress, postCode);
        // const response = await getCoordsUserWithoutPostCode(userAddress);
        setCoordsUser(response.data);
      }
      catch (err) {
        console.log(err);
      }
    };

    handleCoordsUser();

  }, []);


  const arrPointsOfSales: any = [];
  // Si on a récupéré nos points de vente, on stocke juste ce dont on a besoin (id, name, latitude, longitude)
  if (pointOfSales != null) {
    pointOfSales.map((el: any) => {
      arrPointsOfSales.push({
        lat : el.latitude,
        lon : el.longitude,
        id : el.id,
        name : el.name
      });
    })
  }

  const arrCoordsUser: any = [];
  // Pareil pour le client (latitude, longitude) car l'API du Gouvernement nous renvoie un objet plus complexe
  if ((coordsUser != undefined) && (coordsUser.features != undefined)) {
    coordsUser.features.map((el: any) => {
      arrCoordsUser.push({
        lat : el.geometry.coordinates[1],
        lon : el.geometry.coordinates[0]
      });
    });
  }

  // Je lance ma fonction de calcul de la distance avec en paramètres les coordonnées de mon user et le tableau des pizzerias, ainsi qu'un tableau vide pour retourner les résultats
  var resultsDistance: any = [];
  if ((pointOfSales != null) && (coordsUser != undefined) && (coordsUser.features != undefined)) {
    findNearby(arrCoordsUser, arrPointsOfSales, resultsDistance);
  }
  // Je trie le tableau par ordre croissant de distance (de la pizzeria la plus proche à la plus éloignée)
  sortData(resultsDistance , "dist");


  return (
    <IonPage>
      <IonHeader>
        <IonToolbar>
          <IonTitle>Home</IonTitle>
        </IonToolbar>
      </IonHeader>
      <IonContent fullscreen>
        <IonHeader collapse="condense">
          <IonToolbar>
            <IonTitle size="large">Home</IonTitle>
          </IonToolbar>
        </IonHeader>
          { resultsDistance.length > 0 
            ?
            // Et ensuite on slice seulement les X premiers résultats du tableau obtenu, selon combien on veut en afficher au client (ici on veut seulement 2 résultats)
            resultsDistance.slice(0, 2).map((item: any) => (
              <IonCard key={item.id} className="pizza-card" onClick={() => window.location.replace(`/pointofsale/${item.id}`)}>
                <IonImg src={Pizza} className="pizza-card-img" />
                <IonCardHeader>
                  <IonCardTitle>{item.name} <span style={{color: 'lightgreen', display: 'block'}}>[{item.dist.toFixed(2)} KM]</span></IonCardTitle>
                </IonCardHeader>
              </IonCard>
            ))
            :
            <IonCard className="pizza-card">
                <IonCardHeader>
                  <IonCardTitle>Sorry, there are no pizzeria available for this address...</IonCardTitle>
                </IonCardHeader>
              </IonCard>
          }
      </IonContent>
    </IonPage>
  );
};

export default Home;