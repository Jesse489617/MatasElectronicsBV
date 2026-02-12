<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div v-if="assembly" class="rounded bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ assembly.name }} ({{ assembly.id }})</h1>

                <Link
                    v-if="user?.is_admin"
                    :href="`/assemblies/${assembly.id}/edit`"
                    class="rounded-md bg-gray-600 px-4 py-2 text-white transition hover:bg-gray-700"
                >
                    Edit Assembly
                </Link>
            </div>

            <div v-if="assembly.image" class="mb-6">
                <img v-if="assembly.image" :src="`/storage/${assembly.image}`" alt="Assembly Image" class="max-w-full rounded border" />
            </div>

            <p class="mb-4 text-lg font-semibold">Price: €{{ assembly.price }}</p>

            <p class="mt-4 mb-2 text-lg font-semibold">Components in this Assembly:</p>
            <ul class="mb-6 list-inside list-disc">
                <li v-for="comp in assembly.components" :key="comp.id">{{ comp.name }} ({{ comp.type }})</li>
            </ul>

            <div v-if="isAuthenticated" class="border-t pt-4">
                <h2 class="mb-2 text-lg font-semibold">Buy this assembly</h2>

                <div class="flex items-center gap-3">
                    <input type="number" min="1" v-model.number="quantity" class="w-20 rounded border p-2" />

                    <button
                        @click="buyAssembly"
                        :disabled="isBuying"
                        class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700 disabled:opacity-50"
                    >
                        {{ isBuying ? 'Buying...' : 'Buy' }}
                    </button>
                </div>
            </div>

            <div v-else class="border-t pt-4 text-gray-600">
                <p>You must be logged in to purchase this assembly.</p>
            </div>
        </div>

        <div v-else class="p-6 text-gray-500">Loading assembly...</div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { defineProps } from 'vue';
import Nav from '@/components/Nav.vue';
import axios from '@/lib/axios';
import { user, isAuthenticated } from '@/stores/auth';
interface Component {
    id: number;
    name: string;
    type: string;
    price: number;
}

interface Assembly {
    id: number;
    name: string;
    image?: string;
    price: number;
    components: Component[];
}

const props = defineProps<{ id: string }>();

const assembly = ref<Assembly | null>(null);
const quantity = ref(1);
const isBuying = ref(false);

onMounted(async () => {
    try {
        const response = await axios.get(`/api/assemblies/${props.id}`);
        assembly.value = response.data.assembly;
    } catch (err) {
        console.error('Error fetching assembly:', err);
    }
});

const buyAssembly = async () => {
    if (!assembly.value || quantity.value < 1) return;

    isBuying.value = true;

    try {
        const response = await axios.post(
            '/api/assemblies/buy',
            {
                assembly_id: assembly.value.id,
                quantity: quantity.value,
            },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            },
        );

        alert(response.data.message);
        quantity.value = 1;
    } catch (error: any) {
        console.error(error);
        alert(error.response?.data?.message || 'Purchase failed');
    } finally {
        isBuying.value = false;
    }
};
</script>
