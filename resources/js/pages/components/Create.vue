<template>
  <Header />

  <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">Create New Component</h1>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1 font-semibold">Component Name</label>
        <input v-model="name" type="text" class="p-2 border rounded w-full" maxlength="50" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Manufacturer</label>
        <select v-model="manufacturerId" class="p-2 border rounded w-full" required>
          <option value="" disabled>Select manufacturer</option>
          <option v-for="m in manufacturers" :key="m.id" :value="m.id">
            {{ m.name }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Type</label>
        <input v-model="type" type="text" class="p-2 border rounded w-full" maxlength="50" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Image URL</label>
        <input v-model="image" type="text" class="p-2 border rounded w-full" maxlength="255" />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Price (€)</label>
        <input v-model="price" type="number" min="0" step="0.01" class="p-2 border rounded w-full" required />
      </div>

      <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        Create Component
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import Header from '@/components/Header.vue';

    const name = ref('');
    const manufacturerId = ref<number | null>(null);
    const type = ref('');
    const image = ref('');
    const price = ref<number>(0);
    const manufacturers = ref<{id: number, name: string}[]>([]);

    onMounted(async () => {
    try {
        const res = await axios.get('/api/manufacturers', {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        manufacturers.value = res.data.manufacturers;
    } catch (err) {
        console.error('Failed to load manufacturers', err);
    }
    });

    const submit = async () => {
    try {
        await axios.post('/api/components/create', {
        name: name.value,
        manufacturer_id: manufacturerId.value,
        type: type.value,
        image: image.value,
        price: price.value,
        }, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });

        alert('Component created successfully!');

        name.value = '';
        manufacturerId.value = null;
        type.value = '';
        image.value = '';
        price.value = 0;
    } catch (err: any) {
        console.error(err);
        alert(err.response?.data?.message || 'Failed to create component');
    }
    };
</script>
