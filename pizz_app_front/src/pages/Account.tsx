import React from 'react';
import {IonList, IonImg, IonItem, IonLabel, IonIcon, IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonCardSubtitle, IonButton, IonContent, IonHeader, IonPage, IonTitle, IonToolbar } from '@ionic/react';
import { personOutline , layersOutline , ticketOutline , receiptOutline } from 'ionicons/icons';

import './Home.css';

const Account: React.FC = () => {

  return (
    <IonPage>

      <IonHeader>
        <IonToolbar>
          <IonTitle>Account</IonTitle>
        </IonToolbar>
      </IonHeader>

      <IonContent fullscreen>

        <IonHeader collapse="condense">
          <IonToolbar>
            <IonTitle size="large">Account</IonTitle>
          </IonToolbar>
        </IonHeader>

        <IonList>

            <IonItem href="/myinfos">
                <IonIcon icon={personOutline} style={{marginRight: 10}} />
                <IonLabel>My informations</IonLabel>
            </IonItem>

            <IonItem href="/myorders">
                <IonIcon icon={layersOutline} style={{marginRight: 10}} />
                <IonLabel>My orders</IonLabel>
            </IonItem>

            <IonItem>
                <IonIcon icon={ticketOutline} style={{marginRight: 10}} />
                <IonLabel>My discounts</IonLabel>
            </IonItem>

            <IonItem>
                <IonIcon icon={receiptOutline} style={{marginRight: 10}} />
                <IonLabel>Data policy</IonLabel>
            </IonItem>

        </IonList>

      </IonContent>
    </IonPage>
  );
};

export default Account;
