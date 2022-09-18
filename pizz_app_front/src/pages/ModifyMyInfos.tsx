import React, {useEffect, useState} from 'react';

import { useDispatch } from 'react-redux';
import { bindActionCreators } from 'redux';
import { actionsCreators } from '../state/index';

import axios from 'axios';

import { IonTitle, IonLabel, IonItem, IonInput, IonButton,
    IonCol,  IonHeader, IonIcon, IonPage, IonRouterLink, IonRow, IonToolbar , IonImg, useIonRouter} from '@ionic/react';
import styles from './Login.module.css';
import {store} from "../state/store";
import { stringify } from 'querystring';
import Pizza from "../assets/pizzApp.jpg";
import { registrationInfos } from '../utils/api';

import jwt from 'jwt-decode';
import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from "yup";

import { Dispatch } from 'redux';
import { ActionType } from '../state/action-types/index';
import { Action } from '../state/actions/index';


interface userInfosInterface {
    id?: number;
    email?: string;
    firstname?: string;
    lastname?: string;
    address?: string;
    city?: string;
    zipcode?: string;
    phonenumber?: string;
    roles?: [];
}

const schema = yup.object({
    firstname: yup.string().required("Thanks to type your firstname"),
    lastname: yup.string().required("Thanks to type your lastname"),
    address: yup.string().required("Thanks to type your address"),
    zipcode: yup.string().min(5 , "Zipcode must be at least 5 caracters").required("Thanks to type your zipcode").matches(/^[0-9]*$/, "Your zipcode must only contains numbers"),
    city: yup.string().required("Thanks to type your city"),
    phonenumber: yup.string().min(10, "Phone number must be at least 10 caracters").required("Thanks to type your phone number").matches(/^[0-9]*$/, "Your phone number must only contains numbers")
    // phonenumber: yup.string().min(10, "Phone number must be at least 10 caracters").required("Thanks to type your phone number").matches(/^\+?[1-9][0-9]{7,14}$/, "Your phone number must only contains numbers") // for international phone numbers (with a +34 etc..)
}).required();


const ModifyMyInfos: React.FC  = () => {

    const token = store.getState().user.token;
    const userState = store.getState().user.user;
    const userDatas: userInfosInterface = userState[0];

    const { register, handleSubmit, formState: { errors } } = useForm<userInfosInterface>({
        resolver: yupResolver(schema)
    });

    const onSubmit = () => handleModifyMyInfos();
    const onError = (errors: any, e: any) => {console.log(errors, e)};


    const [userInfos, setUserInfos] = useState<userInfosInterface | any>({
        firstname: userDatas.firstname,
        lastname: userDatas.lastname,
        address: userDatas.address,
        zipcode: userDatas.zipcode,
        city: userDatas.city,
        phonenumber: userDatas.phonenumber,
        roles: ["ROLE_CUSTOMER"]
    });


    const handleChange = (e: any) => {
        const { name, value } = e.target;
        setUserInfos({ ...userInfos, [name]: value });
    }


    // Méthode Ionic pour History.push() une page
    const router = useIonRouter();

    const dispatch = useDispatch();


    const handleModifyMyInfos = async () => {

        try 
        {
            const userToPut = 
            {
                ...userInfos,
                email: userDatas.email,
                roles: userInfos.roles,
                id: userDatas.id,
            };
            
            const response = await registrationInfos(userToPut, token);
            const customer = [response.data];
            dispatch({
                type: ActionType.LOGIN_CUSTOMER,
                payload: customer
            })

            if (response.status == 200) 
            {
                // Si tout est ok on retourne sur la page Login avec une petite alerte pour prévenir l'utilisateur
                alert('Your informations have been successfully updated !');
                window.location.replace('/myinfos');
            }    

        } 
        catch (error) 
        {
            return console.log(error);
        }   

    }

    return (
        <IonPage className={ styles.loginPage }>
            <IonHeader>
                <IonToolbar>
                    <IonTitle style={{textAlign:'center'}}>Modify My Infos</IonTitle>
                </IonToolbar>
            </IonHeader>

            <IonItem>
                <IonImg src={Pizza} />
            </IonItem>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Firstname</span></IonLabel>
                        <IonInput
                            type="text"
                            value={userInfos.firstname}
                            onIonChange={(e) => handleChange(e)}
                            {...register("firstname")}
                        >
                        </IonInput>
                        <p>{errors.firstname?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Lastname</span></IonLabel>
                        <IonInput
                            type="text"
                            value={userInfos.lastname}
                            onIonChange={(e) => handleChange(e)}
                            {...register("lastname")}
                        >
                        </IonInput>
                        <p>{errors.lastname?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Address</span></IonLabel>
                        <IonInput
                            type="text"
                            value={userInfos.address}
                            onIonChange={(e) => handleChange(e)}
                            {...register("address")}
                        >
                        </IonInput>
                        <p>{errors.address?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Zipcode</span></IonLabel>
                        <IonInput
                            type="text"
                            value={userInfos.zipcode}
                            onIonChange={(e) => handleChange(e)}
                            {...register("zipcode")}
                        >
                        </IonInput>
                        <p>{errors.zipcode?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>City</span></IonLabel>
                        <IonInput
                            type="text"
                            value={userInfos.city}
                            onIonChange={(e) => handleChange(e)}
                            {...register("city")}
                        >
                        </IonInput>
                        <p>{errors.city?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Phone number</span></IonLabel>
                        <IonInput
                            type="text"
                            value={userInfos.phonenumber}
                            onIonChange={(e) => handleChange(e)}
                            {...register("phonenumber")}
                        >
                        </IonInput>
                        <p>{errors.phonenumber?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonButton expand="block" color="warning" onClick={handleSubmit(onSubmit, onError)}>
                        Validate my profile
                    </IonButton>
                </IonCol>
            </IonRow>
        </IonPage>
    );
};

export default ModifyMyInfos;