<template>
  <Header />
  <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="p-6 bg-white rounded shadow" v-if="component">
      <h1 class="text-2xl font-bold mb-2">Component Details</h1>

      <p>ID: {{ component?.id }}</p>
      <p>Name: {{ component?.name }}</p>
    </div>
    <div v-else class="p-6 text-gray-500">Loading component...</div>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import axios from '@/lib/axios';
  import Header from '@/components/Header.vue';

  interface Component {
    id: number
    name: string
    description?: string
  }

  const props = defineProps<{ id: string  }>()
  const id = Number(props.id)

  const component = ref<Component | null>(null)

  onMounted(async () => {
    const response = await axios.get(`/api/components/${id}`)
    component.value = response.data.component
  })
</script>
