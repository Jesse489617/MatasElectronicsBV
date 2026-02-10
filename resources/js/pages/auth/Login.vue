<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow">
      <h2 class="text-2xl font-bold text-center">Login</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-gray-700">Email</label>
          <input v-model="form.email" type="email" required
            class="w-full px-4 py-2 mt-1 border rounded focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        <div>
          <label class="block text-gray-700">Password</label>
          <input v-model="form.password" type="password" required
            class="w-full px-4 py-2 mt-1 border rounded focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        <div>
          <button type="submit"
            class="w-full bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition">
            Login
          </button>
        </div>

        <p class="text-center text-gray-500 text-sm">
          Don't have an account?
          <Link href="/register" class="text-indigo-600 hover:underline"> Register </Link>
        </p>

        <p v-if="error" class="text-red-500 text-center">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import axios from '@/lib/axios';
import { Link, router } from '@inertiajs/vue3';
import { fetchUser } from '@/stores/auth';

const form = reactive({
  email: '',
  password: '',
});

const error = ref('');

const submit = async () => {
  try {
    const response = await axios.post('/api/user/login', form);
    localStorage.setItem('token', response.data.currentToken);

    await fetchUser();

    router.visit('/'); 
    error.value = '';
  } catch (err: any) {
    error.value = err.response?.data?.error || 'Login failed';
  }
};
</script>
