<template>
    <Nav />

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold">Edit Component</h1>

        <form @submit.prevent="submit" enctype="multipart/form-data">
            <!-- Name -->
            <div class="mb-4">
                <label class="mb-1 block font-semibold">Component Name</label>
                <input v-model="name" type="text" class="w-full rounded border p-2" maxlength="50" required />
            </div>

            <!-- Manufacturer -->
            <div class="mb-4">
                <label class="mb-1 block font-semibold">Manufacturer</label>
                <select v-model="manufacturerId" class="w-full rounded border p-2" required>
                    <option value="" disabled>Select manufacturer</option>
                    <option v-for="m in manufacturers" :key="m.id" :value="m.id">
                        {{ m.name }}
                    </option>
                </select>
            </div>

            <!-- Type -->
            <div class="mb-4">
                <label class="mb-1 block font-semibold">Type</label>
                <input v-model="type" type="text" class="w-full rounded border p-2" maxlength="50" required />
            </div>

            <!-- IMAGE UPLOAD -->
            <div class="mb-4">
                <label class="mb-1 block font-semibold">Upload Image</label>
                <input type="file" @change="handleFileUpload" class="w-full rounded border p-2" accept="image/*" />
            </div>

            <!-- Preview -->
            <div v-if="imagePreview || image" class="mb-4">
                <p class="mb-2 font-semibold">Preview:</p>
                <img :src="imagePreview || `/storage/${image}`" class="h-40 rounded border object-cover" alt="Component Image Preview" />
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label class="mb-1 block font-semibold">Price (€)</label>
                <input v-model.number="price" type="number" min="0" step="0.01" class="w-full rounded border p-2" required />
            </div>

            <button type="submit" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Save Changes</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { defineProps } from 'vue';
import Nav from '@/components/Nav.vue';

const props = defineProps<{ id: string }>();

const name = ref('');
const manufacturerId = ref<number | null>(null);
const type = ref('');
const image = ref(''); // existing image path from backend
const price = ref<number>(0);

const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(null);

const manufacturers = ref<{ id: number; name: string }[]>([]);

onMounted(async () => {
    try {
        // Load manufacturers
        const manuRes = await axios.get('/api/manufacturers', {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        manufacturers.value = manuRes.data.manufacturers;

        // Load component data
        const compRes = await axios.get(`/api/components/${props.id}`);
        const component = compRes.data.component ?? compRes.data;

        name.value = component.name;
        manufacturerId.value = component.manufacturer_id;
        type.value = component.type;
        image.value = component.image ?? '';
        price.value = component.price;
    } catch (err) {
        console.error('Failed to load component', err);
    }
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (!target.files || !target.files.length) return;

    imageFile.value = target.files[0];
    imagePreview.value = URL.createObjectURL(imageFile.value);
};

const submit = async () => {
    try {
        const formData = new FormData();

        formData.append('name', name.value);
        formData.append('manufacturer_id', String(manufacturerId.value));
        formData.append('type', type.value);
        formData.append('price', String(price.value));

        // Only append new image if uploaded
        if (imageFile.value) {
            formData.append('image', imageFile.value);
        }

        await axios.post(`/api/components/${props.id}?_method=PUT`, formData, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'multipart/form-data',
            },
        });

        router.visit(`/components/${props.id}`);
    } catch (err: any) {
        console.error(err.response?.data);
        alert('Failed to update component');
    }
};
</script>
