export interface LoginPayload {
    email: string;
    password: string;
}

export interface RegisterPayload {
    name: string;
    email: string;
    password: string;
}

export interface BuyAssemblyPayload {
    assembly_id: number;
    quantity: number;
}

export interface CreateAssemblyPayload {
    name: string;
    price: number;
    components: number[];
    image?: File | null;
}

export interface CreateCustomAssemblyPayload {
    name: string;
    price: number;
    components: number[];
}

export interface BuyComponentPayload {
    component_id: number;
    quantity: number;
}

export interface CreateComponentPayload {
    name: string;
    manufacturer_id: number;
    type: string;
    price: number;
    image?: File;
}

export interface UpdateComponentPayload {
    id: number;
    name: string;
    manufacturer_id: number;
    type: string;
    price: number;
    image?: File;
}




