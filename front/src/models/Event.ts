export default class Event {
    id?: string;
    atelier?: any;
    date_debut?: any;
    date_fin?: any;
    localisation?: string;
    date_inscription_maximum?: Date;
    reservations?: any[];
    limite_participants?: number;

    get start(): string {
        return this.date_debut!.date ? parseAndFormatDate(this.date_debut!.date) : parseAndFormatDate(this.date_debut)
    }

    get end(): string {
        return this.date_fin!.date ? parseAndFormatDate(this.date_fin!.date) : parseAndFormatDate(this.date_fin)
    }

    get title(): string {
        return this.atelier.nom
    }

    // get backgroundColor(): string {
    //     return "#ECB2C6"
    // }
}

function parseAndFormatDate(dateString: string) {
    // Créer un objet Date à partir de la chaîne de date initiale
    const date = new Date(dateString);
  
    // Obtenir les composantes de la date
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Les mois débutent à 0
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
  
    // Assembler la nouvelle chaîne de date avec l'heure conservée
    const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
  
    return formattedDate;
  }
