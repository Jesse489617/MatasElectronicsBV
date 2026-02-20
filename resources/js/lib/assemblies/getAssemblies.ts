import axios from '@/plugins/axios';
import type { Assembly } from '@/types/interfaces';

export const getAssemblies = async (): Promise<Assembly[]> => {
    const response = await axios.get('/api/assemblies');
    return response.data.data ?? response.data;
};
