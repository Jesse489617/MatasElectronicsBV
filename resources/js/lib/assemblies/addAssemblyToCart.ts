import axios from '@/plugins/axios';
import type { BuyAssemblyPayload } from '@/types/payload';

export const addAssemblyToCart = async (payload: BuyAssemblyPayload) => {
    const response = await axios.post('/api/assemblies/cart', payload);

    return response.data;
};
