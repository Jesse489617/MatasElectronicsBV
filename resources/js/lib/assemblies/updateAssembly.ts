import axios from '@/lib/axios';

export const updateAssembly = async (id: string, formData: FormData) => {
    const response = await axios.post(`/api/assemblies/${id}?_method=PUT`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });

    return response.data;
};
