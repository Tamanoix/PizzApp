import './RouteButton.css';
import {IonButton} from "@ionic/react";

interface ContainerProps {
  name: string;
  latitude:string;
  longitude:string;
 /* wazeRedirection?:
      | ((event: React.MouseEvent<HTMLButtonElement, MouseEvent>) => void)
      | undefined;*/
}

const RouteButton: React.FC<ContainerProps> = ({ name,  latitude,longitude }) => {

    const lat = latitude;
    const lng = longitude;

    // creation of a destination
    let toLat="40.758896";
    let toLong="-73.985130";

    let destination = toLat + ',' + toLong;

    // `https://waze.com/ul?ll="${destination}"&navigate=yes&z=10`
    const wazeRedirection = ():void => {
        window.open( "https://waze.com/ul?ll=45.6906304,-120.810983&z=10");
    }

  return (
    <div className="container">
        <IonButton onClick={() => wazeRedirection()} color="danger">{name}</IonButton>
    </div>
  );
};

export default RouteButton;
