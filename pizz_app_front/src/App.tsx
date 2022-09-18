import { Redirect, Route } from 'react-router-dom';
import React, {useEffect, useState} from "react";
import {useTypedSelector} from "../src/state/hooks/useTypedSelector";
import {
  IonApp,
  IonIcon,
  IonLabel,
  IonRouterOutlet,
  IonTabBar,
  IonTabButton,
  IonTabs,
  setupIonicReact
} from '@ionic/react';
import { IonReactRouter } from '@ionic/react-router';
import { analytics, ellipse, homeOutline, square, triangle, personCircleOutline, logOutOutline } from 'ionicons/icons';
import Home from './pages/Home';
import Account from './pages/Account';
import MyInfos from './pages/MyInfos';
import MyOrders from './pages/MyOrders';
import Login from './pages/Login';
import Register from './pages/Register';
import { userInfo } from 'os';
import './App.css';

import { useDispatch } from 'react-redux';
import { bindActionCreators } from 'redux';
import { actionsCreators } from './state/index';
import RegisterInfos from './pages/RegisterInfos';
import PointOfSale from './pages/PointOfSale';
import ModifyMyInfos from './pages/ModifyMyInfos';



setupIonicReact();

const App: React.FC = () => {

  const [isLogged, setIsLogged] = useState(false);
  const {isAuthenticated, user} = useTypedSelector((state)=> state.user);

  useEffect(() => {

      const redirection = () => {
          // Si l'utilisateur a réussi la connexion
          if (isAuthenticated)
          {
            setIsLogged(true);
          }
          // Sinon si l'utilisateur est vide (il a donc été déconnecté)
          if (isLogged && user.length === 0) {
            setIsLogged(false);
          }
      }

  redirection();

  }, [isAuthenticated]);

  const useActions = () => {
    const dispatch = useDispatch();

    return bindActionCreators(actionsCreators, dispatch);
};

const { logout } = useActions();


  return (
      <IonApp>
        <IonReactRouter>
          <IonTabs>

            <IonRouterOutlet className="main-container">

                    <Route exact path="/home" render={() => {
                      // Si l'utilisateur est connecté, il peut circuler dans l'application, sinon il doit se connecter
                      return isLogged ? <Home  /> : <Login />;
                    }}>
                    </Route>

                    <Route exact path="/account" render={() => {
                        return isLogged ? <Account  /> : <Login />;
                    }}>
                    </Route>

                    <Route exact path="/myinfos" render={() => {
                        return isLogged ? <MyInfos  /> : <Login />;
                    }}>
                    </Route>

                    <Route exact path="/myorders" render={() => {
                        return isLogged ? <MyOrders  /> : <Login />;
                    }}>
                    </Route>

                    <Route exact path="/pointofsale/:id" render={() => {
                        return isLogged ? <PointOfSale /> : <Login />;
                    }}>
                    </Route>

                    <Route exact path="/modifymyinfos" render={() => {
                        return isLogged ? <ModifyMyInfos /> : <Login />;
                    }}>
                    </Route>

                    <Route path="/logout">
                      <Login/>
                    </Route>

                    <Route path="/login">
                      <Login />
                    </Route>

                    <Route path="/register">
                      <Register />
                    </Route>

                    <Route path="/registerinfos">
                      <RegisterInfos />
                    </Route>

                    <Route exact path="/">
                        {!isLogged ?

                            <Redirect to="/login" />

                        :
                            <Redirect to="/home" />
                        }
                    </Route>

            </IonRouterOutlet>

            {/* La barre de navigation du dessous apparaît seulement si on est connecté */}
            <IonTabBar slot="bottom" style={isLogged ? {} : {display: 'none'}}>

                <IonTabButton tab="home" href="/home">
                  <IonIcon icon={homeOutline}/>
                  <IonLabel>Home</IonLabel>
                </IonTabButton>

                <IonTabButton tab="tour" href="/account">
                  <IonIcon icon={personCircleOutline}/>
                  <IonLabel>Account</IonLabel>
                </IonTabButton>

                <IonTabButton tab="logout" href="/logout" onClick={logout}>
                  <IonIcon icon={logOutOutline}/>
                  <IonLabel>Logout</IonLabel>
                </IonTabButton>

            </IonTabBar>

          </IonTabs>

        </IonReactRouter>
      </IonApp>
  );
}




export default App;
