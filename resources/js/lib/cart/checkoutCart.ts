import axios from '@/plugins/axios';

export const checkoutCart = async () => {
    return axios.post('/api/cart/checkout');
};
