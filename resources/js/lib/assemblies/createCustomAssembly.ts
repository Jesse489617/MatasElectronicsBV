import axios from '@/lib/axios';
import type { CreateCustomAssemblyPayload } from '@/types/payload';

export const createCustomAssembly = async (payload: CreateCustomAssemblyPayload) => {
    return axios.post('/api/assemblies/custom', {
        name: payload.name,
        price: payload.price,
        components: payload.components,
    });
};
