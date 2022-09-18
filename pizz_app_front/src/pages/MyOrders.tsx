import React, {useEffect, useState} from 'react';
import axios from 'axios';

import {IonList, IonImg, IonItem, IonLabel, IonIcon, IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonCardSubtitle, IonButton, IonContent, IonHeader, IonPage, IonTitle, IonToolbar } from '@ionic/react';
import { store } from '../state/store';

import './Account.css';
import { getOrders } from '../utils/api';


interface userInfosInterface {
    id?: number;
}


const MyOrders: React.FC = () => {

    const token = store.getState().user.token;
    const dataUser = store.getState().user.user;
    const userInfos: userInfosInterface = dataUser[0];
    const userId = userInfos.id;

    const [orders, setOrders] = useState<any[]>([]);

    useEffect( () => {

        const handleOrders = async () => 
        {
            try 
            {
                const response = await getOrders(userId , token);
                setOrders(response.data);
            } 
            catch (err) 
            {
                console.log(err);
            }
        };

        handleOrders();

    }, []);


    const ordersUnaffected: any = [];
    const ordersWaiting: any = [];
    const ordersFinished: any = [];

    orders.map(order => {
        // On trie les commandes récupérées en fonction de leur statut
        switch (order.status)
        {
            case 0 : 
                ordersUnaffected.push(order);
                break;

            case 1 : 
                ordersWaiting.push(order);
                break;

            case 2 : 
                ordersWaiting.push(order);
                break;

            case 3 : 
                ordersFinished.push(order);
                break;
                
            default : 
                console.log(order);
                break;
        }
    });


    return (
      <div className='my-orders'>
  
        {/* S'il n'y a aucune commande, on affiche un message au client */}
        { orders.length != 0 ? <h2>My orders</h2> : <p style={{textAlign: "center", margin: 30}}>Never passed an order ?</p> }

        {/* S'il y a au moins une commande dans cette catégorie (commande pas encore affectée à un livreur), sinon on n'affiche rien */}
        { ordersUnaffected.length != 0 &&
        <>
            <h3>Orders unaffected yet : </h3>

            {ordersUnaffected.map( (el:any) => {
                var orderTotal = 0;
                return (
                // Code réutilisable, je pourrais en faire un composant
                <div className="orders-block" key={el.id}>
                    <div>
                        <img src="https://assets.afcdn.com/recipe/20210521/120446_w1024h768c1cx1060cy707.jpg" alt="" />
                        <p>Order number : {el.id}</p>
                        <p style={{fontWeight: "bold"}}>Pizzeria : {el.pointOfSale.name}</p>
                        {el.orderDetails.map((obj:any) => {
                            orderTotal = orderTotal + (obj.product.price * obj.quantity);
                            return (
                            <p style={{fontStyle: "italic", fontSize: "12px"}} key={obj.id}>{obj.quantity} x {obj.product.name} at {obj.product.price} € , total : {(obj.product.price * obj.quantity)} €</p>
                        )})}
                        <p style={{textDecoration: "underline"}}>Order's total : {orderTotal} €</p>
                    </div>
                    <p className="order-status">Not yet affected</p>
                </div>
            )})}
        </>
        }
  
        { ordersWaiting.length != 0 &&
        <>
            <h3>Out for delivery orders</h3>

            {ordersWaiting.map( (el:any) => {
                var orderTotal = 0;
                return (
                <div className="orders-block" key={el.id}>
                    <div>
                        <img src="https://assets.afcdn.com/recipe/20210521/120446_w1024h768c1cx1060cy707.jpg" alt="" />
                        <p>Order number : {el.id}</p>
                        <p style={{fontWeight: "bold"}}>Pizzeria : {el.pointOfSale.name}</p>
                        {el.orderDetails.map((obj:any) => {
                            orderTotal = orderTotal + (obj.product.price * obj.quantity);
                            return (
                            <p style={{fontStyle: "italic", fontSize: "12px"}} key={obj.id}>{obj.quantity} x {obj.product.name} at {obj.product.price} € , total : {(obj.product.price * obj.quantity)} €</p>
                        )})}
                        <p style={{textDecoration: "underline"}}>Order's total : {orderTotal} €</p>
                    </div>
                    <p className="order-status">Out for delivery</p>
                </div>
            )})}
        </>
        }
  
        { ordersFinished.length != 0 &&
        <>
            <h3>Delivered orders</h3>

            {ordersFinished.map( (el:any) => {
                var orderTotal = 0;
                return (
                <div className="orders-block" key={el.id}>
                    <div>
                        <img src="https://assets.afcdn.com/recipe/20210521/120446_w1024h768c1cx1060cy707.jpg" alt="" />
                        <p>Order number : {el.id}</p>
                        <p style={{fontWeight: "bold"}}>Pizzeria : {el.pointOfSale.name}</p>
                        {el.orderDetails.map((obj:any) => {
                            orderTotal = orderTotal + (obj.product.price * obj.quantity);
                            return (
                            <p style={{fontStyle: "italic", fontSize: "12px"}} key={obj.id}>{obj.quantity} x {obj.product.name} at {obj.product.price} € , total : {(obj.product.price * obj.quantity)} €</p>
                        )})}
                        <p style={{textDecoration: "underline"}}>Order's total : {orderTotal} €</p>
                    </div>
                    <p className="order-status">Delivered order</p>
                </div>
            )})}
        </>
        }

          <button className='back-btn' onClick={() => window.location.href = '/account'}>Back</button>

      </div>
    );
  };
  
  export default MyOrders;