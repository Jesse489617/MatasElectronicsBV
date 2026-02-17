<template>
    <Nav />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div v-if="component" class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                    <div class="flex justify-center">
                        <div class="aspect-square w-100 overflow-hidden rounded-xl border bg-gray-50 shadow-sm">
                            <img
                                v-if="component.image"
                                :src="component.image.main"
                                alt="Component Image"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full items-center justify-center text-gray-400">No Image</div>
                        </div>
                    </div>

                    <div class="flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ component.name }}</h1>
                                    <p class="mt-1 text-sm text-gray-500">Component ID: {{ component.id }}</p>
                                </div>

                                <Link
                                    v-if="user?.is_admin"
                                    :href="`/components/${component.id}/edit`"
                                    class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300"
                                >
                                    Edit
                                </Link>
                            </div>

                            <div class="mt-6">
                                <p class="text-3xl font-semibold text-gray-900">€{{ component.price }}</p>
                            </div>
                        </div>

                        <div class="mt-10 border-t pt-6">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Specifications</h2>
                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Type</span>
                                    <span class="font-medium text-gray-900">{{ component.type }}</span>
                                </div>
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
                                        @click="handleBuyComponent"
                                        :disabled="isBuying"
                                        class="flex-1 rounded-md bg-black px-6 py-3 font-medium text-white transition hover:bg-gray-800 disabled:opacity-50"
                                    >
                                        {{ isBuying ? 'Processing...' : 'Add to Cart' }}
                                    </button>

                                    <button
                                        @click="handleAddToCart"
                                        :disabled="isBuying"
                                        class="flex items-center justify-center rounded-md bg-black p-3 text-white transition hover:bg-gray-800 disabled:opacity-50"
                                    >
                                        <ShoppingCartIcon class="h-6 w-6" />
                                    </button>
                                </div>
                            </div>

                            <div v-else class="text-sm text-gray-500">You must be logged in to purchase this component.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="p-6 text-gray-500">Loading component...</div>
    </div>
</template>

<script setup lang="ts">
import { ShoppingCartIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, defineProps } from 'vue';
import Nav from '@/components/Nav.vue';
import { addComponentToCart } from '@/lib/components/addComponentToCart';
import { buyComponent } from '@/lib/components/buyComponent';
import { getComponentById } from '@/lib/components/getComponentById';
import { user, isAuthenticated } from '@/stores/auth';
import type { Component } from '@/types/interfaces';

const props = defineProps<{ id: string }>();
const id = Number(props.id);

const component = ref<Component | null>(null);
const quantity = ref(1);
const isBuying = ref(false);
const isAdding = ref(false);

onMounted(async () => {
    try {
        component.value = await getComponentById(id);
    } catch (err) {
        console.error('Error fetching component:', err);
    }
});

const handleBuyComponent = async () => {
    if (!component.value || quantity.value < 1) return;

    isBuying.value = true;

    try {
        const data = await buyComponent({
            component_id: component.value.id,
            quantity: quantity.value,
        });

        alert(data.message);
        quantity.value = 1;
    } catch (err: any) {
        console.error(err);
        alert(err.response?.data?.message || 'Purchase failed');
    } finally {
        isBuying.value = false;
    }
};

const handleAddToCart = async () => {
    if (!component.value || quantity.value < 1) return;

    isAdding.value = true;

    try {
        const data = await addComponentToCart({
            component_id: component.value.id,
            quantity: quantity.value,
        });

        alert(data.message);
        quantity.value = 1;
    } catch (error: any) {
        console.error(error);
        alert(error.response?.data?.message || 'Failed to add to cart');
    } finally {
        isAdding.value = false;
    }
};
</script>
