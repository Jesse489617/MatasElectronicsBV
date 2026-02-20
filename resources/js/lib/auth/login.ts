import axios from '@/plugins/axios';
import type { LoginPayload } from '@/types/payload';
import type { LoginResponse } from '@/types/response';

export const login = async (payload: LoginPayload): Promise<LoginResponse> => {
    const response = await axios.post('/api/user/login', payload);
    return response.data;
};
