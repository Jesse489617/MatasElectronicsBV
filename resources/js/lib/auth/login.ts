import axios from '@/lib/axios';
import type { LoginResponse } from '@/types/response';
import type { LoginPayload } from '@/types/payload';

export const login = async (payload: LoginPayload): Promise<LoginResponse> => {
    const response = await axios.post('/api/user/login', payload);
    return response.data;
};
