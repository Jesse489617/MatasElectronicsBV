<template>
    <Nav />
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div v-if="component" class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                    <!-- Component Image -->
                    <div class="flex justify-center">
                        <div class="aspect-square w-100 overflow-hidden rounded-xl border bg-gray-50 shadow-sm">
                            <img
                                v-if="component.image"
                                :src="`/storage/${component.image}`"
                                alt="Component Image"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full items-center justify-center text-gray-400">No Image</div>
                        </div>
                    </div>

                    <!-- Component Info -->
                    <div class="flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">
                                        {{ component.name }}
                                    </h1>
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

                            <!-- Price -->
                            <div class="mt-6">
                                <p class="text-3xl font-semibold text-gray-900">€{{ component.price }}</p>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="mt-10 border-t pt-6">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Specifications</h2>

                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Type</span>
                                    <span class="font-medium text-gray-900">
                                        {{ component.type }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="p-6 text-gray-500">Loading component...</div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Nav from '@/components/Nav.vue';
import axios from '@/lib/axios';
import { user } from '@/stores/auth';

interface Component {
    id: number;
    name: string;
    price: number;
    type: string;
    image?: string;
}

const props = defineProps<{ id: string }>();
const id = Number(props.id);

const component = ref<Component | null>(null);

onMounted(async () => {
    const response = await axios.get(`/api/components/${id}`);
    component.value = response.data.component;
});
</script>
