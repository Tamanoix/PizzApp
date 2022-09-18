import React, {useEffect, useState} from 'react';
import axios from 'axios';

import {IonList, IonImg, IonItem, IonLabel, IonIcon, IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonCardSubtitle, IonButton, IonContent, IonHeader, IonPage, IonTitle, IonToolbar , IonRow, IonCol } from '@ionic/react';
import { store } from '../state/store';
import Pizza from "../assets/pizzApp.jpg";

import './Account.css';
import { useParams } from 'react-router';
import { getPointOfSaleById, getProducts, addNewOrder, addOrderDetails } from '../utils/api';
import { current } from '@reduxjs/toolkit';


type PointOfSaleParams = {
    id: string;
    name: string;
    address: string;
    city: string;
    zipcode: string;
};

type ProductsParams = {
    category: object;
    name: string;
    price: number;
    description: string;
};

interface ModalInterface {
    product: string[],
    quantity: number
}

interface OrderInterface {
    orderProduct: [
        {
            name: string,
            price: number,
            quantity: number,
            id: number
        }
    ],
}

interface userInfosInterface {
    id?: number;
}


const PointOfSale: React.FC = () => {

    const token = store.getState().user.token;
    const dataUser = store.getState().user.user;
    const userInfos: userInfosInterface = dataUser[0];
    const userId = userInfos.id;

    const { id }= useParams<PointOfSaleParams>();

    const [pointOfSale, setPointOfSale] = useState<PointOfSaleParams>();
    const [products, setProducts] = useState<ProductsParams[]>([]);
    const [modal, setModal] = useState<ModalInterface | any>();
    const [order, setOrder] = useState<OrderInterface[] | any>([]);
    const [isReloaded, setIsReloaded] = useState(false);


    useEffect( () => {

        // On récupère les infos de notre point de vente
        const handlePointOfSale = async () => 
        {
            try 
            {
                const response = await getPointOfSaleById(id , token);
                setPointOfSale(response.data);
            } 
            catch (err) 
            {
                console.log(err);
            }
        };

        handlePointOfSale();

        // Et on récupère nos produits
        const handleProducts = async () => 
        {
            try 
            {
                const response = await getProducts(token);
                setProducts(response.data);
            } 
            catch (err) 
            {
                console.log(err);
            }
        };

        handleProducts();

    }, []);

    const addQuantity = () => {
        setModal({...modal, quantity: ((modal.quantity)+1)});
    };

    const reduceQuantity = () => {
        // On s'assure que le client ne puisse pas choisir moins de 1 exemplaire d'un produit
        if (modal.quantity === 1) {
            setModal({...modal, quantity: 1});
            return;
        }
        else {
            setModal({...modal, quantity: ((modal.quantity)-1)});
            if (modal.quantity < 1) {
                setModal({...modal, quantity: 1})
            };
        }
    };

    const removeProduct = (productId: number) => {
        setOrder((current: any) =>
            current.filter((orderProduct: any) => {
                return orderProduct.id !== productId;
            }),
        );
    };


    let isInArray = false;

    const addToOrder = (nameProduct: string , quantityProduct: any , productId: any) => {

        // S'il y a déjà des produits dans la commande
        if (order.length !== 0) 
        {
            // Pour savoir si le produit existe déjà dans notre commande, on boucle sur la commande
            for (let i=0 ; i<order.length ; i++) 
            {
                if (order[i].name == nameProduct)
                {
                    isInArray = true;
                    // Si le produit existe, on modifie juste sa quantité, et on sort de la boucle
                    return ( order[i].quantity = (order[i].quantity + quantityProduct) , setIsReloaded(!isReloaded) );
                }
                else 
                {
                    isInArray = false;
                    // Sinon si on ne trouve pas le même produit dans la commande, on poursuit
                }
            }
            if (isInArray == true) 
            {
                return console.log("Si on arrive dans ce console.log, c'est pas normal.......");
            }
            else
            {
                // Et donc on ajoute un nouvel objet dans la commande
                return setOrder((current: any) => [...current, {name: modal.product[0], price: modal.product[1], quantity: modal.quantity , id: modal.product[2]}] );
            }
        }
        // Sinon cela veut dire que la commande est encore vide, on peut push notre produit sans problème
        else
        {
            setOrder((current: any) => [...current, {name: modal.product[0], price: modal.product[1], quantity: modal.quantity , id: modal.product[2]}] );
        }
    };

    let totalOrder = 0;

    // Fonction pour générer une référence de la commande aléatoirement, en indiquant simplement la longueur souhaitée
    function makeReference(length: number) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) 
        {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    };

    const reference = makeReference(15);
    const pointOfSaleId = pointOfSale?.id;

    var orderToPush = {
        reference: reference,
        pointOfSale: `/api/point_of_sales/${pointOfSaleId}`,
        customer: `/api/users/${userId}`,
        status: 0
    }

    const handleAddOrder = async () => {

        try 
        {
            // On crée le nouvel order
            const response = await addNewOrder(orderToPush , token);
            console.log(response.data);

            // On stocke l'id du nouvel order
            const orderId = response.data.id;

            for (let i=0 ; i<order.length ; i++) {

                // Pour chaque produit dans la commande, il faut créer son détail dans la table intermédiaire OrderDetail
                var orderDetailsToPush = {
                    quantity: order[i].quantity,
                    product: `/api/products/${order[i].id}`,
                    command: `/api/orders/${orderId}`,
                    subtotal: (order[i].price * order[i].quantity)
                }

                try {
                    // On met à jour le détail de la commande pour chaque produit (OrderDetail)
                    const responseDetails = await addOrderDetails(orderDetailsToPush, token);
                    console.log(responseDetails.data);
                    
                    // Si tout est ok, on redirige le client vers la page de ses commandes avec un message de réussite
                    alert("Successlly placed order !");
                    window.location.replace('/myorders');
                }
                catch (error)
                {
                    console.log(error);
                }
            }
        }
        catch (error) 
        {
            console.log(error);
        }

    }


    return ( 
        <>
            { ((pointOfSale !== null) && (pointOfSale !== undefined) && (products !== null) && (products !== undefined))
            ?
                <div style={{padding: 30}}>

                    <div className='pizzeria-infos'>
                        <IonImg src={Pizza} className="pizza-card-img" style={{width: '80%', margin: 'auto'}} />
                        <p><span className='bold-one'>Pizzeria name :</span> {pointOfSale.name}</p>
                        <p><span className='bold-one'>Address :</span> {pointOfSale.address}</p>
                        <p><span className='bold-one'>City :</span> {pointOfSale.city}</p>
                        <p><span className='bold-one'>Postal Code :</span> {pointOfSale.zipcode}</p>
                    </div>

                    <h3 style={{textAlign: "center", color: 'red', fontWeight: "bold", margin: "30px"}}>Chose any product to pass an order :</h3>

                    {/* On affiche un select avec la liste de nos produits */}
                    <div className='select-container'>
                        <select className="products-infos" onChange={(e) => setModal({...modal, product: (e.target.value).split(','), quantity: 1})}>
                            {
                                products.map((el: any , index: any) => (
                                    <option value={[el.name , el.price , el.id]} key={index}>
                                        {el.name} ({el.price}€)
                                    </option>
                                ))
                            }
                        </select>
                    </div>

                    {/* On affiche un champ de confirmation pour que le client soit sûr de bien choisir son produit */}
                    { ((modal !== null) && (modal !== undefined))
                        &&
                        <div className='modal'>
                            <p><span className='bold-one'>Selected product :</span> {modal.product[0]} ({modal.product[1]}€)</p>
                            <div className='quantity' style={{display: 'flex'}}>
                                <button onClick={reduceQuantity}>-</button>
                                <p>Quantity : {modal.quantity}</p>
                                <button onClick={addQuantity}>+</button>
                            </div>
                            {/* Au click, on ajoute donc le produit et la bonne quantité à la commande (en vérifiant s'il s'y trouve déjà ou pas) */}
                            <button onClick={() => addToOrder(modal.product[0] , modal.quantity , modal.product[2])} style={{padding: "10px"}}>Add to order</button>
                        </div>
                    }

                    {/* Et ensuite on affiche le détail de la commande */}
                    { ((order !== null) && (order !== undefined) && (order.length !== 0))
                        &&
                        <div className='order'>
                            <h3 style={{textAlign: "center", color: 'red', fontWeight: "bold", margin: "30px"}}>Your order :</h3>

                            <table className="total-order-table">
                                <thead>
                                    <tr>
                                        <td><span className='bold-one'>Remove</span></td>
                                        <td><span className='bold-one'>Quantity</span></td>
                                        <td><span className='bold-one'>Product</span></td>
                                        <td><span className='bold-one'>Price</span></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        order.map((el: any , index: any) => {
                                            {/* Pour chaque produit de la commande, on ajoute au prix total */}
                                            totalOrder = (totalOrder + (el.price * el.quantity));
                                            return ( 
                                                <tr key={index}>
                                                    <td onClick={() => removeProduct(el.id)}><span className='suppr'>x</span></td>
                                                    <td>{el.quantity}</td>
                                                    <td>{el.name}</td>
                                                    <td>{el.price}€</td>
                                                </tr>
                                            )
                                        })
                                    }
                                    <tr style={{background: "red", color: "black", fontWeight: "bold"}}>
                                        <td colSpan={3}>Total</td>
                                        <td>{totalOrder}€</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    }

                <IonRow>
                    <IonCol>
                        <IonButton expand="block" color="warning" style={{marginTop: 20}} onClick={handleAddOrder}>
                            Pass my order !
                        </IonButton>
                    </IonCol>
                </IonRow>
                    

                </div>
            :
                <p>Loading...</p>
            }
        </>
    );

  };
  
  export default PointOfSale;