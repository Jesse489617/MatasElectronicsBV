export interface ImageAsset {
    main: string;
    icon: string;
}

export interface Assembly {
    id: number;
    name: string;
    image: ImageAsset | null;
    price: number;
}

export interface Component {
    id: number;
    name: string;
    manufacturer_id: number;
    type: string;
    image: ImageAsset | null;
    price: number;
}

export interface AssemblyComponents {
    id: number;
    name: string;
    image: ImageAsset | null;
    price: number;
    components: Component[];
}

export interface Manufacturer {
    id: number;
    name: string;
}
