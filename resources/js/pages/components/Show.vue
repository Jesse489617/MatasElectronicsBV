<template>
    <Nav />
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded bg-white p-6 shadow" v-if="component">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ component.name }} ({{ component.id }})</h1>
                <Link
                    v-if="user?.is_admin"
                    :href="`/components/${component.id}/edit`"
                    class="rounded-md bg-gray-600 px-4 py-2 text-white transition hover:bg-gray-700"
                >
                    Edit Component
                </Link>
            </div>

            <p class="mb-4 text-lg font-semibold">Price: €{{ component.price }}</p>
            <p class="mb-4 text-lg font-semibold">Type: {{ component.type }}</p>
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
}

const props = defineProps<{ id: string }>();
const id = Number(props.id);

const component = ref<Component | null>(null);

onMounted(async () => {
    const response = await axios.get(`/api/components/${id}`);
    component.value = response.data.component;
});
</script>
