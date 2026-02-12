<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50">
        <div class="w-full max-w-md space-y-6 rounded bg-white p-8 shadow">
            <h2 class="text-center text-2xl font-bold">Register</h2>

            <form @submit.prevent="handleRegister" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="mt-1 w-full rounded border px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>

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
                    <button type="submit" class="w-full rounded bg-indigo-600 px-4 py-2 text-white transition hover:bg-indigo-700">Register</button>
                </div>

                <p class="text-center text-sm text-gray-500">
                    Already have an account?
                    <Link href="/login" class="text-indigo-600 hover:underline"> Login </Link>
                </p>

                <p v-if="error" class="text-center text-red-500">{{ error }}</p>
                <p v-if="success" class="text-center text-green-500">{{ success }}</p>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

import { register } from '@/lib/auth/register';

const form = reactive({
    name: '',
    email: '',
    password: '',
});

const error = ref('');
const success = ref('');

const handleRegister = async () => {
    try {
        await register(form);

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
