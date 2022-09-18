import React, {useEffect, useState} from 'react';

import { useDispatch } from 'react-redux';
import { bindActionCreators } from 'redux';
import { actionsCreators } from '../state/index';

import { IonTitle, IonLabel, IonItem, IonInput, IonButton,
    IonCol,  IonHeader, IonIcon, IonPage, IonRouterLink, IonRow, IonToolbar , IonImg } from '@ionic/react';
import styles from './Login.module.css';
import {store} from "../state/store";
import Pizza from "../assets/pizzApp.jpg";
import Logo from "../assets/pizzapp-img.png";
import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from "yup";


interface userInterface {
    username?: string;
    password?: string;
    firstname?: string;
    lastname?: string;
    phonenumber?:string;
    roles?:string[];
    number?:number;
}

const schema = yup.object({
    username: yup.string().email("Thanks to type a valid email").required("Thanks to type your email"),
    // password: yup.string().required("Thanks to type your password").matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/,
        // "Your password must contain at least 8 caracters, a capital letter, a lowercase letter, a number and a special character")
    password: yup.string().required("Thanks to type your password")
}).required();


const Login: React.FC = () => {

    const { register, handleSubmit, formState: { errors } } = useForm<userInterface>({
        resolver: yupResolver(schema)
    });

    // Au submit du formulaire, si tout est ok pour les champs avec Yup et React-hook-form, on va lancer notre action de connexion (qui contient les appels Axios)
    const onSubmit = () => handleLogin();
    const onError = (errors: any, e: any) => console.log(errors, e);


    const useActions = () => {
        const dispatch = useDispatch();

        return bindActionCreators(actionsCreators, dispatch);
    };

    const { loginUser } = useActions();


    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');


    // On lance l'action loginUser qui se situe dans state > action-creators > index.ts
    const handleLogin = async () => {

        const userObj = {
            username:username,
            password: password
        }

        loginUser(username, userObj);

    }


    return (
        <IonPage className={ styles.loginPage }>
            <IonHeader>
                <IonToolbar>
                    <IonTitle style={{textAlign:'center'}}>Login</IonTitle>
                </IonToolbar>
            </IonHeader>

            { store.getState().user.loading ? <div className="absolute-loader">Loading...</div> : '' }

            <IonItem>
                <IonImg src={Pizza} />
            </IonItem>
            
            <IonRow>
                <IonCol>
                    <IonItem>
                        <IonLabel position="floating"><span className='bold-one'>Email</span></IonLabel>
                        <IonInput
                            type="email"
                            value={username}
                            onIonChange={(e) => setUsername(e.detail.value!)}
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
                            value={password}
                            onIonChange={(e) => setPassword(e.detail.value!)}
                            {...register("password")}
                        >
                        </IonInput>
                        <p>{errors.password?.message}</p>
                    </IonItem>
                </IonCol>
            </IonRow>
            <IonRow>
                <IonCol>
                    <p style={{ fontSize: "small" }}>
                        By clicking LOGIN you agree to our <a href="#">Policy</a>
                    </p>
                    <IonButton expand="block" color="warning" onClick={handleSubmit(onSubmit, onError)}>
                        Login
                    </IonButton>
                    <p style={{ fontSize: "medium" }}>
                        New to Pizzapp ? <a href="/register">Sign up !</a>
                    </p>
                </IonCol>
            </IonRow>
        </IonPage>
    );
};

export default Login;