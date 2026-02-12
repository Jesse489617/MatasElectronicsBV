<template>
    <Nav />

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold">Create New Component</h1>

        <form @submit.prevent="handleCreateComponent" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="mb-1 block font-semibold">Component Name</label>
                <input v-model="name" type="text" class="w-full rounded border p-2" maxlength="50" required />
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Manufacturer</label>
                <select v-model="manufacturerId" class="w-full rounded border p-2" required>
                    <option value="" disabled>Select manufacturer</option>
                    <option v-for="m in manufacturers" :key="m.id" :value="m.id">
                        {{ m.name }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Type</label>
                <input v-model="type" type="text" class="w-full rounded border p-2" maxlength="50" required />
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Upload Image</label>
                <input type="file" @change="handleFileUpload" class="w-full rounded border p-2" accept="image/*" />
            </div>

            <div v-if="imagePreview" class="mb-4">
                <p class="mb-2 font-semibold">Preview:</p>
                <img :src="imagePreview" class="h-40 rounded border object-cover" alt="Component Image Preview" />
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Price (€)</label>
                <input v-model="price" type="number" min="0" step="0.01" class="w-full rounded border p-2" required />
            </div>

            <button type="submit" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Create Component</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Nav from '@/components/Nav.vue';
import { createComponent } from '@/lib/components/createComponent';
import { getManufacturers } from '@/lib/manufacturers/getManufacturers';

const name = ref('');
const manufacturerId = ref<number | null>(null);
const type = ref('');
const price = ref<number>(0);

const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(null);

const manufacturers = ref<{ id: number; name: string }[]>([]);

onMounted(async () => {
    try {
        manufacturers.value = await getManufacturers();
    } catch (err) {
        console.error('Failed to load manufacturers', err);
    }
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (!target.files || !target.files.length) return;

    imageFile.value = target.files[0];
    imagePreview.value = URL.createObjectURL(imageFile.value);
};

const handleCreateComponent = async () => {
    if (!name.value) return alert('Component name is required');
    if (!manufacturerId.value) return alert('Select a manufacturer');
    if (!type.value) return alert('Component type is required');

    try {
        await createComponent({
            name: name.value,
            manufacturer_id: manufacturerId.value,
            type: type.value,
            price: price.value,
            image: imageFile.value ?? undefined,
        });

        router.visit('/components');
    } catch (err) {
        console.error(err);
        alert('Failed to create component');
    }
};
</script>
