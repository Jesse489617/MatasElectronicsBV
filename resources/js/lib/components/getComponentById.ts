import axios from '@/plugins/axios';
import type { Component } from '@/types/interfaces';

export const getComponentById = async (id: number | string): Promise<Component> => {
    const response = await axios.get(`/api/components/${id}`);
    return response.data.data ?? response.data;
};
