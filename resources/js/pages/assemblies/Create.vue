<template>
    <Nav />
    <div class="mx-auto max-w-3xl py-10">
        <h1 class="mb-6 text-2xl font-bold">Create New Assembly</h1>

        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="mb-1 block">Assembly Name</label>
                <input v-model="name" type="text" class="w-full rounded border p-2" required />
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Upload Image</label>
                <input type="file" @change="handleFileUpload" class="w-full rounded border p-2" accept="image/*" />
            </div>

            <div v-if="imagePreview" class="mb-4">
                <p class="mb-2 font-semibold">Preview:</p>
                <img :src="imagePreview" class="h-40 rounded border object-cover" alt="Assembly Image Preview" />
            </div>

            <div class="mb-4">
                <label class="mb-1 block">Select Components</label>
                <div v-for="component in components" :key="component.id" class="mb-1 flex items-center">
                    <input type="checkbox" :value="component.id" v-model="selectedComponents" />
                    <span class="ml-2">{{ component.name }} — €{{ component.price }}</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Total Component Price (€)</label>
                <input type="number" :value="totalPrice" class="w-full rounded border bg-gray-100 p-2" readonly />
            </div>

            <div class="mb-4">
                <label class="mb-1 block">Assembly Price (€)</label>
                <input v-model.number="price" type="number" class="w-full rounded border p-2" :min="totalPrice" />
                <p class="mt-1 text-sm text-gray-500">Minimum price based on selected components: €{{ totalPrice }}</p>
            </div>

            <button type="submit" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Create Assembly</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import Nav from '@/components/Nav.vue';
import { createAssembly } from '@/lib/assemblies/createAssembly';
import { getComponents } from '@/lib/components/getComponents';

const name = ref('');
const price = ref<number | null>(null);
const components = ref<any[]>([]);
const selectedComponents = ref<number[]>([]);

const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(null);

onMounted(async () => {
    try {
        const response = await getComponents();
        components.value = response.data ?? response;
    } catch (err) {
        console.error('Failed to load components', err);
    }
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (!target.files?.length) return;

    imageFile.value = target.files[0];
    imagePreview.value = URL.createObjectURL(imageFile.value);
};

const totalPrice = computed(() => {
    return selectedComponents.value.reduce((sum, compId) => {
        const comp = components.value.find((c) => c.id === compId);
        return sum + Number(comp?.price ?? 0);
    }, 0);
});

const submit = async () => {
    if (!name.value) return alert('Assembly name is required');
    if (!selectedComponents.value.length) return alert('Select at least one component');

    const finalPrice = price.value ?? totalPrice.value;

    if (price.value !== null && price.value < totalPrice.value) {
        const confirmLower = confirm(`The assembly price (€${price.value}) is lower than the total component price (€${totalPrice.value}). Proceed?`);
        if (!confirmLower) return;
    }

    try {
        await createAssembly({
            name: name.value,
            price: finalPrice,
            components: selectedComponents.value,
            image: imageFile.value,
        });

        router.visit('/assemblies');
    } catch (err) {
        console.error(err);
        alert('Failed to create assembly.');
    }
};
</script>
