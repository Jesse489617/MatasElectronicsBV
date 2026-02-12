<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div v-if="assembly" class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">

                    <div class="flex justify-center">
                        <div class="aspect-square w-100 overflow-hidden rounded-xl border bg-gray-50 shadow-sm">
                            <img v-if="assembly.image" :src="`/storage/${assembly.image}`" alt="Assembly Image" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full items-center justify-center text-gray-400">No Image</div>
                        </div>
                    </div>

                    <div class="flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">
                                        {{ assembly.name }}
                                    </h1>
                                    <p class="mt-1 text-sm text-gray-500">Assembly ID: {{ assembly.id }}</p>
                                </div>

                                <Link
                                    v-if="user?.is_admin"
                                    :href="`/assemblies/${assembly.id}/edit`"
                                    class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300"
                                >
                                    Edit
                                </Link>
                            </div>

                            <div class="mt-6">
                                <p class="text-3xl font-semibold text-gray-900">€{{ assembly.price }}</p>
                            </div>
                        </div>

                        <div class="mt-10 border-t pt-6">
                            <div v-if="isAuthenticated">
                                <div class="flex items-center gap-4">
                                    <input
                                        type="number"
                                        min="1"
                                        v-model.number="quantity"
                                        class="w-24 rounded-md border border-gray-300 px-3 py-2 text-center focus:border-black focus:outline-none"
                                    />

                                    <button
                                        @click="buyAssembly"
                                        :disabled="isBuying"
                                        class="flex-1 rounded-md bg-black px-6 py-3 font-medium text-white transition hover:bg-gray-800 disabled:opacity-50"
                                    >
                                        {{ isBuying ? 'Processing...' : 'Add to Cart' }}
                                    </button>
                                </div>
                            </div>

                            <div v-else class="text-sm text-gray-500">You must be logged in to purchase this assembly.</div>
                        </div>
                    </div>
                </div>

                <div class="mt-16">
                    <h2 class="mb-6 text-2xl font-semibold text-gray-900">Included Components</h2>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="comp in assembly.components"
                            :key="comp.id"
                            :href="`/components/${comp.id}`"
                            class="flex items-center justify-between overflow-hidden rounded-lg border bg-gray-50 transition hover:shadow-md"
                        >
                            <div class="px-4 py-2 text-sm font-medium text-gray-900">
                                {{ comp.name }} <span class="text-gray-500">({{ comp.type }})</span>
                            </div>

                            <div class="h-12 w-12 shrink-0 overflow-hidden rounded-r-lg">
                                <img
                                    v-if="comp.image"
                                    :src="`/storage/${comp.image.replace('components/', 'components/icons/')}`"
                                    alt="Component Icon"
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="flex h-full w-full items-center justify-center bg-gray-100 text-xs text-gray-400">No Icon</div>
                            </div>
                        </Link>
                    </div>
                </div>
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
    image?: string;
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
