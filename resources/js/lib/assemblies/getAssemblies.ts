import axios from '@/lib/axios';
import type { Assembly } from '@/types/interfaces';

export const getAssemblies = async (): Promise<Assembly[]> => {
    const response = await axios.get('/api/assemblies');
    return response.data.assemblies ?? response.data;
};
