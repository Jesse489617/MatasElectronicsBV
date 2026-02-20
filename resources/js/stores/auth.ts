import { ref } from 'vue';
import axios from '@/plugins/axios';

export const user = ref<any>(null);
export const isAuthenticated = ref(false);

export const fetchUser = async () => {
  const token = localStorage.getItem('token');
  if (!token) {
    user.value = null;
    isAuthenticated.value = false;
    return;
  }

  try {
    const res = await axios.get('/api/user', {
      headers: { Authorization: `Bearer ${token}` },
    });
    user.value = res.data.user;
    isAuthenticated.value = true;
  } catch {
    user.value = null;
    isAuthenticated.value = false;
  }
};


export const logout = async () => {
  const token = localStorage.getItem('token');
  if (!token) {
    user.value = null;
    isAuthenticated.value = false;
    return;
  }

  try {
    await axios.post('/api/user/logout', {}, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
  } catch (error) {
      console.log(error);
  } finally {
    localStorage.removeItem('token');
    user.value = null;
    isAuthenticated.value = false;
  }
};

