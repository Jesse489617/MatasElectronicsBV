import axios from '@/plugins/axios';

export const getComponents = async () => {
    const response = await axios.get('/api/components');
    return response.data.data ?? response.data;
};
