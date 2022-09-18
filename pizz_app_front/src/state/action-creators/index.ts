import axios from 'axios';
import { Dispatch } from 'redux';
import { ActionType } from '../action-types/index';
import { store } from "../store";
import { Action } from '../actions/index';
import { loginCheck, getUser } from '../../utils/api';


export const loginUser = (email: string, userObject: {}) => {

    return async (dispatch: Dispatch<Action>) => {
        // On commence par dispatcher un user vide dans toute l'application (loading en cours)
        dispatch({
            type: ActionType.LOGIN_USER
        });

        try {
            // On effectue le loginCheck pour voir si les identifiants sont bons (1er appel Axios)
            const response = await loginCheck(userObject);
            const token = response.data.token;

            // Si tout est ok, on récupère un token qui permettra d'accéder aux données de la BDD via les autres requêtes, et on le dispatch dans le store
            dispatch({
                type: ActionType.LOGIN_USER_SUCCESS,
                payload: token
            })


            try {
                // Cette fois, avec le token, on récupère les données de l'utilisateur
                const response = await getUser(email, token);

                // Et on dispatch ces données dans le store
                dispatch({
                    type: ActionType.LOGIN_CUSTOMER,
                    payload: response.data
                })

                // Si pas d'erreur de connexion, on redirige le client vers la homepage
                window.location.replace('/home');
            }

            catch (err: any) {
                dispatch({
                    type: ActionType.LOGIN_USER_ERROR,
                    payload: err.message
                })
            }

        }
        catch (err: any) {
            // La 1ère requête Axios (loginCheck) n'a pas réussi. Si l'utilisateur s'est trompé d'identifiants, on lui indique un message d'erreur
            if ((err.response.data.message) == 'Invalid credentials.') 
            {
                alert('Invalid username and password !');
            }
            dispatch({
                type: ActionType.LOGIN_USER_ERROR,
                payload: err.message
            })
        }
    }
}


export const logout = () => {

    return async (dispatch: Dispatch<Action>) => {
        
        // On lance une action qui vide toutes les données présentes dans le store.getState().user
        dispatch({
            type: ActionType.LOGOUT,
        });

        // On vide aussi le localStorage (hydraté par le middleware), et on redirige l'utilisateur vers la page de login
        localStorage.clear();
        alert('Successfully disconnected !');
        window.location.replace('/login');

    }
}

