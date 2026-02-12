<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-4 text-2xl font-bold">Available Assemblies</h1>

        <div v-if="isAuthenticated" class="mb-4 flex items-center gap-4">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search assemblies..."
                class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:outline-none"
            />

            <Link v-if="user?.is_admin" href="/assemblies/create" class="rounded-md bg-gray-600 px-4 py-2 whitespace-nowrap text-white transition hover:bg-gray-700">
                + Add Assembly
            </Link>
        </div>

        <ul class="space-y-4">
            <li v-for="assembly in filteredAssemblies" :key="assembly.id" class="rounded bg-white p-4 shadow hover:shadow-lg">
                <Link :href="`/assemblies/${assembly.id}`" class="font-semibold text-gray-600">
                    {{ assembly.name }}
                </Link>
            </li>
        </ul>

        <p v-if="filteredAssemblies.length === 0" class="mt-4 text-gray-500">No assemblies found.</p>
    </div>
</template>

<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';
    import { ref, computed, onMounted } from 'vue';
    import Nav from '@/components/Nav.vue';
    import axios from '@/lib/axios';

    import { user, isAuthenticated } from '@/stores/auth';

    interface Assembly {
        id: number;
        name: string;
    }

    const allAssemblies = ref<Assembly[]>([]);
    const searchQuery = ref('');

    const fetchAssemblies = async () => {
        try {
            const response = await axios.get('/api/assemblies');
            allAssemblies.value = response.data.assemblies ?? response.data;
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
