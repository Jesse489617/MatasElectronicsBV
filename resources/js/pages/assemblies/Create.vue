<template>
    <div class="py-6">
        <h1 class="mb-6 text-2xl font-bold">Create New Assembly</h1>

        <form @submit.prevent="submit" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="mb-1 block">Assembly Name</label>
                <input v-model="form.name" type="text" class="w-full rounded border p-2" required />
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Upload Image</label>
                <input type="file" accept="image/*" @change="handleFileChange" class="w-full rounded border p-2" />
            </div>

            <div v-if="imagePreview" class="mb-4">
                <p class="mb-2 font-semibold">Preview:</p>
                <img :src="imagePreview" class="h-40 rounded border object-cover" alt="Assembly Image Preview" />
            </div>

            <div class="mb-4">
                <label class="mb-1 block">Select Components</label>
                <div v-for="component in components" :key="component.id" class="mb-1 flex items-center">
                    <input type="checkbox" :value="component.id" v-model="form.components" />
                    <span class="ml-2">{{ component.name }} — €{{ component.price }}</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Total Component Price (€)</label>
                <input type="number" :value="totalPrice" class="w-full rounded border bg-gray-100 p-2" readonly />
            </div>

            <div class="mb-4">
                <label class="mb-1 block">Assembly Price (€)</label>
                <input v-model.number="form.price" type="number" class="w-full rounded border p-2" :min="totalPrice" />
                <p class="mt-1 text-sm text-gray-500">Minimum price based on selected components: €{{ totalPrice }}</p>
            </div>

            <button :disabled="form.processing" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Create Assembly</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue';
import { ref, onMounted, computed } from 'vue';
import useNotifications from '@/composables/useNotifications';
import { getComponents } from '@/lib/components/getComponents';
import type { Component } from '@/types/resources/components/Component';

const components = ref<Component[]>([]);
const imagePreview = ref<string | null>(null);

const { notifySuccess, notifyError } = useNotifications();

const form = useForm('post', route('api.assemblies.store'), {
    name: '',
    image: null as File | null,
    price: null as number | null,
    components: [] as number[],
});

onMounted(async () => {
    try {
        const response = await getComponents();
        components.value = response.data ?? response;
    } catch (err) {
        console.error('Failed to load components', err);
    }
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (target.files && target.files.length > 0) {
        form.image = target.files[0];
        form.validate('image');
        imagePreview.value = URL.createObjectURL(target.files[0]);
    }
};

const totalPrice = computed(() => {
    return form.components.reduce((sum, componentId) => {
        const component = components.value.find((c) => c.id === componentId);
        return sum + Number(component?.price ?? 0);
    }, 0);
});

const submit = async () => {
    try {
        await form.submit();
        notifySuccess('The assembly has been saved successfully.');
        router.visit('/assemblies');
    } catch {
        notifyError('Something went wrong while loading the assemblies.');
    }
};

// const submit = async () => {
//     if (!name.value) return alert('Assembly name is required');
//     if (!selectedComponents.value.length) return alert('Select at least one component');
//
//     const finalPrice = price.value ?? totalPrice.value;
//
//     if (price.value !== null && price.value < totalPrice.value) {
//         const confirmLower = confirm(`The assembly price (€${price.value}) is lower than the total component price (€${totalPrice.value}). Proceed?`);
//         if (!confirmLower) return;
//     }
//
//     try {
//         await createAssembly({
//             name: name.value,
//             price: finalPrice,
//             components: selectedComponents.value,
//             image: imageFile.value,
//         });
//
//         router.visit('/assemblies');
//     } catch (err) {
//         console.error(err);
//         alert('Failed to create assembly.');
//     }
// };
</script>
