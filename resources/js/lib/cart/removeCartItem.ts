import axios from '@/lib/axios';

export const removeCartItem = async (id: number) => {
    return axios.delete(`/api/cart/items/${id}`);
};
