<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold">Purchase History</h1>

        <div class="mb-6 flex flex-col gap-4 sm:flex-row">
            <input v-model="search" type="text" placeholder="Search assemblies or components..." class="w-full rounded border p-2" />

            <select v-model="sortBy" class="w-48 rounded border p-2">
                <option value="date">Sort by date</option>
                <option value="name">Sort by name</option>
                <option value="price">Sort by price</option>
            </select>
        </div>

        <div v-for="item in filteredHistory" :key="item.id" class="mb-6 rounded bg-white p-6 shadow">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-semibold">
                        {{ item.name }}
                        <span v-if="item.type === 'component'" class="text-sm text-gray-400">(Component)</span>
                    </h2>
                    <p class="text-sm text-gray-500">Purchased on {{ formatDate(item.created_at) }}</p>
                </div>

                <div class="text-right font-semibold">€{{ item.price }}</div>
            </div>

            <div v-if="item.type === 'assembly' && item.components?.length" class="mt-4">
                <p class="mb-1 font-semibold">Components</p>
                <ul class="list-inside list-disc">
                    <li v-for="component in item.components" :key="component.id">{{ component.name }}</li>
                </ul>
            </div>
        </div>

        <p v-if="filteredHistory.length === 0" class="text-center text-gray-500">No purchases found.</p>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed, onMounted } from 'vue';
import Nav from '@/components/Nav.vue';
import { isAuthenticated, fetchUser } from '@/stores/auth';

const history = ref<any[]>([]);
const search = ref('');
const sortBy = ref<'date' | 'name' | 'price'>('date');

onMounted(async () => {
    await fetchUser();

    if (!isAuthenticated.value) {
        router.visit('/login');
        return;
    }

    try {
        const response = await axios.get('/api/history', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });

        history.value = response.data.history.map((row: any) => {
            if (row.type === 'assembly' && row.assembly) {
                return {
                    id: row.id,
                    type: 'assembly',
                    name: row.assembly.name ?? 'Unnamed Assembly',
                    price: row.assembly.price ?? 0,
                    components: row.assembly.components ?? [],
                    created_at: row.created_at,
                };
            } else if (row.type === 'component' && row.component) {
                return {
                    id: row.id,
                    type: 'component',
                    name: row.component.name ?? 'Unnamed Component',
                    price: row.component.price ?? 0,
                    created_at: row.created_at,
                };
            }
            return {
                id: row.id,
                type: 'unknown',
                name: 'Unknown Purchase',
                price: 0,
                created_at: row.created_at,
            };
        });
    } catch (error) {
        console.error('Failed to load history:', error);
    }
});

const filteredHistory = computed(() => {
    let result = [...history.value];

    if (search.value) {
        const q = search.value.toLowerCase();
        result = result.filter((item) => {
            const nameMatch = item.name.toLowerCase().includes(q);
            const compMatch = item.type === 'assembly' && item.components?.some((c: any) => c.name.toLowerCase().includes(q));
            return nameMatch || compMatch;
        });
    }

    if (sortBy.value === 'name') {
        result.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy.value === 'price') {
        result.sort((a, b) => a.price - b.price);
    } else {
        result.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime());
    }

    return result;
});

const formatDate = (date: string) => new Date(date).toLocaleDateString();
</script>
