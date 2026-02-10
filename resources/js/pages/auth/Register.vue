<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow">
      <h2 class="text-2xl font-bold text-center">Register</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-gray-700">Name</label>
          <input v-model="form.name" type="text" required
            class="w-full px-4 py-2 mt-1 border rounded focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

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
            Register
          </button>
        </div>

        <p class="text-center text-gray-500 text-sm">
          Already have an account?
          <Link href="/login" class="text-indigo-600 hover:underline"> Login </Link>
        </p>

        <p v-if="error" class="text-red-500 text-center">{{ error }}</p>
        <p v-if="success" class="text-green-500 text-center">{{ success }}</p>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import axios from '@/lib/axios';
import { Link, router } from '@inertiajs/vue3';

const form = reactive({
  name: '',
  email: '',
  password: '',
});

const error = ref('');
const success = ref('');

const submit = async () => {
  try {
    await axios.post('/api/user/register', form);
    success.value = 'Account created successfully! Redirecting to login...';
    error.value = '';
    setTimeout(() => {
      router.visit('/login');
    }, 1500);
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Registration failed';
    success.value = '';
  }
};
</script>
