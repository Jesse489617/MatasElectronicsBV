import axios from '@/lib/axios';

export const getComponents = async () => {
    const response = await axios.get('/api/components');
    return response.data.components ?? response.data;
};
