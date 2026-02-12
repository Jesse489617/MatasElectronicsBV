<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50">
        <div class="w-full max-w-md space-y-6 rounded bg-white p-8 shadow">
            <h2 class="text-center text-2xl font-bold">Login</h2>

            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="mt-1 w-full rounded border px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label class="block text-gray-700">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        class="mt-1 w-full rounded border px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <button type="submit" class="w-full rounded bg-indigo-600 px-4 py-2 text-white transition hover:bg-indigo-700">Login</button>
                </div>

                <p class="text-center text-sm text-gray-500">
                    Don't have an account?
                    <Link href="/register" class="text-indigo-600 hover:underline"> Register </Link>
                </p>

                <p v-if="error" class="text-center text-red-500">{{ error }}</p>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import { login } from '@/lib/auth/login';
import { fetchUser } from '@/stores/auth';

const form = reactive({
    email: '',
    password: '',
});

const error = ref('');

const handleLogin = async () => {
    try {
        const data = await login(form);

        localStorage.setItem('token', data.currentToken);

        await fetchUser();

        router.visit('/');
        error.value = '';
    } catch (err: any) {
        error.value = err.response?.data?.error || 'Login failed';
    }
};
</script>
