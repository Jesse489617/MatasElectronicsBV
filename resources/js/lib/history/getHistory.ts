import axios from '@/plugins/axios';

export const getHistory = async () => {
    const response = await axios.get('/api/history');
    return response.data.history ?? [];
};
