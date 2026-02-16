import axios from '@/lib/axios';

export const checkoutCart = async () => {
    return axios.post('/api/cart/checkout');
};
