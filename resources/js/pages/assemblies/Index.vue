<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-900">Available Assemblies</h1>

        <div v-if="isAuthenticated" class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search assemblies..."
                class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:outline-none"
            />

            <Link href="/assemblies/custom" class="rounded-md bg-gray-600 px-4 py-2 whitespace-nowrap text-white transition hover:bg-gray-700">
                + Custom Assembly
            </Link>

            <Link
                v-if="user?.is_admin"
                href="/assemblies/create"
                class="rounded-md bg-gray-600 px-4 py-2 whitespace-nowrap text-white transition hover:bg-gray-700"
            >
                + Add Assembly
            </Link>
        </div>

        <div class="space-y-4">
            <Link
                v-for="assembly in filteredAssemblies"
                :key="assembly.id"
                :href="`/assemblies/${assembly.id}`"
                class="flex items-center justify-between overflow-hidden rounded-lg border bg-gray-50 transition hover:shadow-md"
            >
                <div class="px-4 py-2 text-sm font-medium text-gray-900">
                    {{ assembly.name }}
                </div>

                <div class="h-12 w-12 shrink-0 overflow-hidden rounded-r-lg">
                    <img v-if="assembly.image" :src="assembly.image.icon" alt="Assembly Image" class="h-full w-full object-cover" />
                    <div v-else class="flex h-full w-full items-center justify-center bg-gray-100 text-xs text-gray-400">No Image</div>
                </div>
            </Link>
        </div>

        <p v-if="filteredAssemblies.length === 0" class="mt-4 text-gray-500">No assemblies found.</p>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import Nav from '@/components/Nav.vue';
import { getAssemblies } from '@/lib/assemblies/getAssemblies';
import { user, isAuthenticated } from '@/stores/auth';
import type { Assembly } from '@/types/interfaces';

const allAssemblies = ref<Assembly[]>([]);
const searchQuery = ref('');

const fetchAssemblies = async () => {
    try {
        allAssemblies.value = await getAssemblies();
    } catch (error) {
        console.error('Failed to fetch assemblies:', error);
    }
};

onMounted(fetchAssemblies);

const filteredAssemblies = computed(() => {
    if (!isAuthenticated.value || !searchQuery.value) {
        return allAssemblies.value;
    }

    return allAssemblies.value.filter((assembly) => assembly.name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});
</script>
