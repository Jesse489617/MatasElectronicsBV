import axios from '@/lib/axios';
import type { CreateComponentPayload } from '@/types/payload';

export const createComponent = async (payload: CreateComponentPayload) => {
    const formData = new FormData();

    formData.append('name', payload.name);
    formData.append('manufacturer_id', String(payload.manufacturer_id));
    formData.append('type', payload.type);
    formData.append('price', String(payload.price));

    if (payload.image) {
        formData.append('image', payload.image);
    }

    const response = await axios.post('/api/components/create', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    });

    return response.data;
};
