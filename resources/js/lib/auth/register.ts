import axios from '@/lib/axios';
import type { RegisterPayload } from '@/types/payload';

export const register = async (payload: RegisterPayload) => {
    const response = await axios.post('/api/user/register', payload);
    return response.data;
};
