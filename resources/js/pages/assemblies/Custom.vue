<template>
    <Nav />

    <div class="mx-auto max-w-3xl py-10">
        <h1 class="mb-6 text-2xl font-bold">Create Your Custom Assembly</h1>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="mb-1 block">Assembly Name</label>
                <input v-model="name" type="text" class="w-full rounded border p-2" required />
            </div>

            <div class="mb-4">
                <label class="mb-1 block">Select Components</label>
                <div v-for="component in components" :key="component.id" class="mb-1 flex items-center">
                    <input type="checkbox" :value="component.id" v-model="selectedComponents" />
                    <span class="ml-2">{{ component.name }} — €{{ component.price }}</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Assembly Price (€)</label>
                <input type="number" :value="totalPrice" class="w-full rounded border bg-gray-100 p-2" readonly />
                <p class="mt-1 text-sm text-gray-500">Price is automatically calculated based on selected components.</p>
            </div>

            <button type="submit" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Create Assembly</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import Nav from '@/components/Nav.vue';
import { createCustomAssembly } from '@/lib/assemblies/createCustomAssembly';
import { getComponents } from '@/lib/components/getComponents';

const name = ref('');
const components = ref<any[]>([]);
const selectedComponents = ref<number[]>([]);

onMounted(async () => {
    try {
        components.value = await getComponents();
    } catch (err) {
        console.error('Failed to load components', err);
    }
});

const totalPrice = computed(() => {
    return selectedComponents.value.reduce((sum, compId) => {
        const comp = components.value.find((c) => c.id === compId);
        return sum + Number(comp?.price ?? 0);
    }, 0);
});

const submit = async () => {
    if (!name.value) return alert('Assembly name is required');
    if (!selectedComponents.value.length) return alert('Select at least one component');

    try {
        await createCustomAssembly({
            name: name.value,
            price: totalPrice.value,
            components: selectedComponents.value,
        });

        router.visit('/assemblies');
    } catch (err) {
        console.error(err);
        alert('Failed to create custom assembly.');
    }
};
</script>
