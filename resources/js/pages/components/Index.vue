<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <h1 class="mb-6 text-2xl font-bold text-gray-900">Available Components</h1>

        <div v-if="isAuthenticated" class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search components..."
                class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:outline-none"
            />

            <Link
                v-if="user?.is_admin"
                href="/components/create"
                class="rounded-md bg-gray-600 px-4 py-2 whitespace-nowrap text-white transition hover:bg-gray-700"
            >
                + Add Component
            </Link>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Link
                v-for="component in filteredComponents"
                :key="component.id"
                :href="`/components/${component.id}`"
                class="flex items-center justify-between overflow-hidden rounded-lg border bg-gray-50 transition hover:shadow-md"
            >

                <div class="px-4 py-2 text-sm font-medium text-gray-900">
                    {{ component.name }}
                </div>

                <div class="h-12 w-12 shrink-0 overflow-hidden rounded-r-lg">
                    <img
                        v-if="component.image"
                        :src="`/storage/${component.image.replace('components/', 'components/icons/')}`"
                        alt="Component Icon"
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="flex h-full w-full items-center justify-center bg-gray-100 text-xs text-gray-400">No Icon</div>
                </div>
            </Link>
        </div>

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
    image?: string;
    name: string;
    type?: string;
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
