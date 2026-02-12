import axios from '@/lib/axios';
import type { CreateAssemblyPayload } from '@/types/payload';

export const createAssembly = async (payload: CreateAssemblyPayload) => {
    const formData = new FormData();

    formData.append('name', payload.name);
    formData.append('price', String(payload.price));

    payload.components.forEach((id) => {
        formData.append('components[]', String(id));
    });

    if (payload.image) {
        formData.append('image', payload.image);
    }

    return axios.post('/api/assemblies/create', formData);
};
