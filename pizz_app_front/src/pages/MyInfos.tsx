import React from 'react';
import {IonList, IonImg, IonItem, IonLabel, IonIcon, IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonCardSubtitle, IonButton, IonContent, IonHeader, IonPage, IonTitle, IonToolbar } from '@ionic/react';
import { store } from '../state/store';
import { createOutline } from 'ionicons/icons';

import './Account.css';


interface userInfosInterface {
    id?: number;
    firstname?: string;
    lastname?: string;
    address?: string;
    city?: string;
    zipcode?: string;
    email?: string;
    phonenumber?: string;
}


const MyInfos: React.FC = () => {

    const data = store.getState().user.user;
    const userInfos: userInfosInterface = data[0];

    return (
        <div className='my-informations'>
    
            <h2>My personnal informations</h2>
            <div className="infos-block">
                <div>
                    <div>
                        <p><span className='bold-one'>Firstname :</span> {userInfos.firstname}</p>
                        <p><span className='bold-one'>Lastname :</span> {userInfos.lastname}</p>
                    </div>
                    <p><span className='bold-one'>Email :</span> {userInfos.email}</p>
                    <p><span className='bold-one'>Phone :</span> {userInfos.phonenumber}</p>
                </div>
                <div>
                    <p><span className='bold-one'>Address :</span> {userInfos.address}</p>
                    <p><span className='bold-one'>Postal Code :</span> {userInfos.zipcode}</p>
                    <p><span className='bold-one'>City :</span> {userInfos.city}</p>
                </div>
            </div>

            <IonButton color="warning" onClick={() => window.location.replace('/modifymyinfos')}>
                <IonIcon slot="icon-only" icon={createOutline} />
                Modify my informations
            </IonButton>

            <button className='back-btn' onClick={() => window.location.href = '/account'}>Back</button>
  
        </div>
    );
  };
  
  export default MyInfos;