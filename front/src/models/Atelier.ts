import Product from "@/models/Product";

export default class Atelier {
    id?: string;
    thematique?: string;
    products?: Product[];
    nom?: string;
    description?: string;
    prix?: number;
}
