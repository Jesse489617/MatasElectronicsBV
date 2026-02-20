import axios from '@/plugins/axios';
import type { BuyAssemblyPayload } from '@/types/payload';

export const buyAssembly = async (payload: BuyAssemblyPayload) => {
    const response = await axios.post('/api/assemblies/buy', payload);

    return response.data;
};
