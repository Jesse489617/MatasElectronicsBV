<template>
    <Nav />

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold">Edit Component</h1>

        <form @submit.prevent="submit">
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
                <label class="mb-1 block font-semibold">Image URL</label>
                <input v-model="image" type="text" class="w-full rounded border p-2" maxlength="255" />
            </div>

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
const image = ref('');
const price = ref<number>(0);
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

const submit = async () => {
    try {
        await axios.put(
            `/api/components/${props.id}`,
            {
                name: name.value,
                manufacturer_id: manufacturerId.value,
                type: type.value,
                image: image.value,
                price: price.value,
            },
            {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
            },
        );

        router.visit(`/components/${props.id}`);
    } catch (err: any) {
        console.error(err.response?.data);
        alert('Failed to update component');
    }
};
</script>
