import axios from '@/lib/axios';
import type { BuyComponentPayload } from '@/types/payload';

export const addComponentToCart = async (payload: BuyComponentPayload) => {
    const response = await axios.post('/api/components/cart', payload);
    return response.data;
};
