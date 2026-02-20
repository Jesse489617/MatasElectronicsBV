import axios from '@/plugins/axios';
import type { BuyComponentPayload } from '@/types/payload';

export const buyComponent = async (payload: BuyComponentPayload) => {
    const response = await axios.post('/api/components/buy', payload);

    return response.data;
};
