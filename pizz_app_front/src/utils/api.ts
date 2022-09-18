import axios from 'axios';
import PointOfSale from '../pages/PointOfSale';
import { store } from '../state';


// On stocke toutes nos URLs d'appels API ici (on peut utiliser le "process.env.base_url" pour éviter d'avoir notre URL de base (ici https://localhost:8000) accessible)
const BASE_GOUV_API_URL = "https://api-adresse.data.gouv.fr/search";
const BASE_DEFAULT_API_URL = "https://localhost:8000/api";
const API_URL_REGISTER = "https://localhost:8000/register";


// const token = localStorage.getItem('token');
// const token = store.getState().user.token;

// const config =
// {
//     headers: {
//         'Accept': 'application/json',
//         'Authorization': `Bearer ${token}`,
//         'Access-Control-Allow-Methods': 'GET, OPTIONS, POST, PUT, PATCH, DELETE',
//     },
// };


export async function getPointOfSales(token: any) {
    return (await axios.get(`${BASE_DEFAULT_API_URL}/point_of_sales`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Methods': 'GET, OPTIONS, POST, PUT, PATCH, DELETE',
        },
    }));
}


export async function getPointOfSaleById(id: any, token: any) {
    return (await axios.get(`${BASE_DEFAULT_API_URL}/point_of_sales/${id}`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Methods': 'GET, OPTIONS, POST, PUT, PATCH, DELETE',
        },
    }));
}


export async function getOrders(userId: number | undefined , token: any) {
    return (await axios.get(`${BASE_DEFAULT_API_URL}/orders?customer=${userId}`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Methods': 'GET, OPTIONS, POST, PUT, PATCH, DELETE',
        },
    }));
}


export async function getProducts(token: any) {
    return (await axios.get(`${BASE_DEFAULT_API_URL}/products`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Methods': 'GET, OPTIONS, POST, PUT, PATCH, DELETE',
        },
    }));
}


export async function getCoordsUserWithoutPostCode(userAddress: any) {
    return (await axios.get(`${BASE_GOUV_API_URL}/?q=${userAddress}`));
}


export async function getCoordsUser(userAddress: string, postCode: string) {
    return (await axios.get(`${BASE_GOUV_API_URL}/?q=${userAddress}&postcode=${postCode}`));
}


// export async function getUser(email: any) {
//     return (await axios.get(`${BASE_DEFAULT_API_URL}/users?email=${email}`, config));
// }

export async function getUser(email: any, token: any) {
    return (await axios.get(`${BASE_DEFAULT_API_URL}/users?email=${email}`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Access-Control-Allow-Methods': 'GET, OPTIONS, POST, PUT, PATCH, DELETE',
        },
    }));
}


export async function loginCheck(userObject: any) {
    return (await axios.post(`${BASE_DEFAULT_API_URL}/login_check`, userObject,
        {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Access-Control-Allow-Origin': '*',
                'X-Requested-With': 'XMLHttpRequest', // type de requête
            }
        }))
}


export async function registrationCredentials(email: any, password: any) {
    return (await axios.post(API_URL_REGISTER, {
        email: email,
        password: password
    }, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Access-Control-Allow-Origin': '*',
        }
    }))
}


export async function registrationInfos(userInfos: any, token: any) {
    return (await axios.put(`${BASE_DEFAULT_API_URL}/users/${userInfos.id}`, userInfos,
        {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type',
            }
        }))
}


export async function addNewOrder(orderInfos: any, token: any) {
    return (await axios.post(`${BASE_DEFAULT_API_URL}/orders`, orderInfos,
        {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type',
            }
        }))
}


export async function addOrderDetails(orderDetailsInfos: any, token: any) {
    return (await axios.post(`${BASE_DEFAULT_API_URL}/order_details`, orderDetailsInfos,
        {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type',
            }
        }))
}

