import axios from '@/lib/axios';
import type { AssemblyComponents } from '@/types/interfaces';

export const getAssemblyById = async (id: string | number): Promise<AssemblyComponents> => {
    const response = await axios.get(`/api/assemblies/${id}`);
    return response.data.data ?? response.data;
};
