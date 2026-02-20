import axios from '@/plugins/axios';

export const getCart = async () => {
    const response = await axios.get('/api/cart');
    return response.data;
};
