import React, { useEffect, useState } from 'react';

import { useDispatch } from 'react-redux';
import { bindActionCreators } from 'redux';
import { actionsCreators } from '../state/index';

import axios from 'axios';

import {
    IonTitle, IonLabel, IonItem, IonInput, IonButton,
    IonCol, IonHeader, IonIcon, IonPage, IonRouterLink, IonRow, IonToolbar, IonImg, useIonRouter
} from '@ionic/react';
import styles from './Login.module.css';
import { store } from "../state/store";
import { stringify } from 'querystring';
import Pizza from "../assets/pizzApp.jpg";
import { registrationCredentials, loginCheck } from '../utils/api';
import RegisterInfos from './RegisterInfos';

import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from "yup";


interface credentialsInterface {
    username: string,
    password: string,
    confirmPassword: string
}

// Schéma de validation Yup (avec React-hook-form)
const schema = yup.object({
    username: yup.string().email("Thanks to type a valid email").required("Thanks to type your email"),
    password: yup.string().required("Thanks to type your password").matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/,
        "Your password must contain at least 8 caracters, a capital letter, a lowercase letter, a number and a special character"),
    confirmPassword: yup.string().required("You must confirm your password").oneOf([yup.ref("password"), null], "Passwords must be the same")
}).required();


const Register: React.FC = () => {


    const { register, handleSubmit, formState: { errors } } = useForm<credentialsInterface>({
        resolver: yupResolver(schema)
    });

    const onSubmit = () => handleRegister();
    const onError = (errors: any, e: any) => console.log(errors, e);


    const [credentials, setCredentials] = useState<credentialsInterface | any>({
        username: '',
        password: '',
        confirmPassword: ''
    });


    const handleChange = (e: any) => {
        const { name, value } = e.target;
        setCredentials({ ...credentials, [name]: value });
    }


    // Méthode Ionic pour History.push() une page
    const router = useIonRouter();


    const handleRegister = async () => {

        try {
            const response = await registrationCredentials(credentials.username, credentials.password);
            const userId = response.data[0].id;
            if (response.data.success == "success") {
                try {
                    const userObj = {
                        username: credentials.username,
                        password: credentials.password
                    }
                    const responseCheck = await loginCheck(userObj);
                    const token = responseCheck.data.token;

                    // On stocke le token et l'id dans le localStorage pour les passer sur la page suivante
                    localStorage.setItem('token', token);
                    localStorage.setItem('userId', userId);
                    // localStorage.setItem('email', credentials.username); // On aurait pu également faire passer l'email de la même façon

                    // Si tout est ok on push sur la page RegisterInfos
                    router.push('/registerinfos');
                }
                catch (error) {
                    return console.log(error);
                }
            }
            else {
                return console.log('unable to register correctly')
            }
        }
        catch (err) {
            console.log(err);
        }

    };


    return (
        <IonPage className={styles.loginPage}>
            <IonHeader>
                <IonToolbar>
                    <IonTitle style={{ textAlign: 'center' }}>Register</IonTitle>
                </IonToolbar>
            </IonHeader>

            <IonItem>
                <IonImg src={Pizza} />
            </IonItem>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Email</span></IonLabel>
                        <IonInput
                            type="email"
                            onIonChange={(e) => handleChange(e)}
                            {...register("username")}
                        >
                        </IonInput>
                        <p>{errors.username?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Password</span></IonLabel>
                        <IonInput
                            type="password"
                            onIonChange={(e) => handleChange(e)}
                            {...register("password")}
                        >
                        </IonInput>
                        <p>{errors.password?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Confirm Password</span></IonLabel>
                        <IonInput
                            type="password"
                            {...register("confirmPassword")}
                        >
                        </IonInput>
                        <p>{errors.confirmPassword?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>

            <IonRow>
                <IonCol>
                    <IonButton expand="block" color="warning" onClick={handleSubmit(onSubmit, onError)}>
                        Register
                    </IonButton>
                    <p style={{ fontSize: "medium" }}>
                        Already have an account ? <a href="/login">Login !</a>
                    </p>
                </IonCol>
            </IonRow>

        </IonPage>
    );
};

export default Register;