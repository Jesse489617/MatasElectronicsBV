import axios from '@/plugins/axios';
import type { UpdateComponentPayload } from '@/types/payload';
export const updateComponent = async (payload: UpdateComponentPayload) => {
    const formData = new FormData();

    formData.append('name', payload.name);
    formData.append('manufacturer_id', String(payload.manufacturer_id));
    formData.append('type', payload.type);
    formData.append('price', String(payload.price));

    if (payload.image) formData.append('image', payload.image);

    const response = await axios.post(`/api/components/${payload.id}?_method=PUT`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    });

    return response.data;
};
