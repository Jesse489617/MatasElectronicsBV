import axios from '@/plugins/axios';

export const downloadInvoice = async (id: number) => {
    const response = await axios.get(`/api/history/${id}/invoice`, {
        responseType: 'blob',
    });

    return response.data;
};
