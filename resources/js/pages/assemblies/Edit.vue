<template>
    <Nav />
    <div class="mx-auto max-w-3xl py-10">
        <h1 class="mb-6 text-2xl font-bold">Edit Assembly</h1>

        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="mb-1 block">Assembly Name</label>
                <input v-model="name" type="text" class="w-full rounded border p-2" required />
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Upload Image</label>
                <input type="file" @change="handleFileUpload" class="w-full rounded border p-2" accept="image/*" />
            </div>

            <div v-if="imagePreview || image" class="mb-4">
                <p class="mb-2 font-semibold">Preview:</p>
                <img :src="imagePreview || `/storage/${image}`" class="h-40 rounded border object-cover" alt="Assembly Image Preview" />
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

            <button type="submit" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Save Changes</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { defineProps } from 'vue';
import Nav from '@/components/Nav.vue';

const props = defineProps<{ id: string }>();

const name = ref('');
const image = ref(''); // path to existing image
const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(null);

const price = ref<number | null>(null);
const components = ref<any[]>([]);
const selectedComponents = ref<number[]>([]);

onMounted(async () => {
    try {
        const res = await axios.get('/api/components');
        components.value = res.data.components ?? res.data;

        const assemblyRes = await axios.get(`/api/assemblies/${props.id}`);
        const assembly = assemblyRes.data.assembly;

        name.value = assembly.name;
        image.value = assembly.image ?? '';
        price.value = assembly.price;
        selectedComponents.value = assembly.components.map((c: any) => c.id);
    } catch (err) {
        console.error('Failed to load data', err);
    }
});

const totalPrice = computed(() => {
    return selectedComponents.value.reduce((sum, compId) => {
        const comp = components.value.find((c) => c.id === compId);
        return sum + Number(comp?.price ?? 0);
    }, 0);
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (!target.files || !target.files.length) return;

    imageFile.value = target.files[0];
    imagePreview.value = URL.createObjectURL(imageFile.value);
};

const submit = async () => {
    if (!name.value) return alert('Assembly name is required');
    if (!selectedComponents.value.length) return alert('Select at least one component');

    if (price.value !== null && price.value < totalPrice.value) {
        const confirmLower = confirm(`The assembly price (€${price.value}) is lower than the total component price (€${totalPrice.value}). Proceed?`);
        if (!confirmLower) return;
    }

    try {
        const formData = new FormData();
        formData.append('name', name.value);
        formData.append('price', String(price.value ?? totalPrice.value));
        selectedComponents.value.forEach((id) => formData.append('components[]', String(id)));

        if (imageFile.value) {
            formData.append('image', imageFile.value);
        }

        await axios.post(`/api/assemblies/${props.id}?_method=PUT`, formData, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'multipart/form-data',
            },
        });

        router.visit(`/assemblies/${props.id}`);
    } catch (err) {
        console.error(err);
        alert('Failed to update assembly.');
    }
};
</script>
