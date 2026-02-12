import axios from '@/lib/axios';
import type { Manufacturer } from '@/types/interfaces';

export const getManufacturers = async (): Promise<Manufacturer[]> => {
    const response = await axios.get('/api/manufacturers');
    return response.data.manufacturers;
};
