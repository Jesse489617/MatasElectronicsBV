<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-4 text-2xl font-bold">Available Components</h1>

        <div v-if="isAuthenticated" class="mb-4 flex items-center gap-4">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search components..."
                class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:outline-none"
            />

            <Link
                v-if="user?.is_admin" href="/components/create" class="rounded-md bg-gray-600 px-4 py-2 whitespace-nowrap text-white transition hover:bg-gray-700">
                + Add Component
            </Link>
        </div>

        <ul class="space-y-4">
            <li v-for="component in filteredComponents" :key="component.id" class="rounded bg-white p-4 shadow hover:shadow-lg">
                <Link :href="`/components/${component.id}`" class="font-semibold text-gray-600">
                    {{ component.name }}
                </Link>
            </li>
        </ul>

        <p v-if="filteredComponents.length === 0" class="mt-4 text-gray-500">No components found.</p>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import Nav from '@/components/Nav.vue';
import axios from '@/lib/axios';
import { user, isAuthenticated } from '@/stores/auth';

interface Component {
    id: number;
    name: string;
}

const allComponents = ref<Component[]>([]);
const searchQuery = ref('');

const fetchComponents = async () => {
    try {
        const response = await axios.get('/api/components');
        allComponents.value = response.data.components ?? response.data;
    } catch (error) {
        console.error('Failed to fetch components:', error);
    }
};

onMounted(fetchComponents);

const filteredComponents = computed(() => {
    if (!isAuthenticated.value || !searchQuery.value) {
        return allComponents.value;
    }

    return allComponents.value.filter((component) => component.name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});
</script>
