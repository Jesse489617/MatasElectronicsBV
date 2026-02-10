<template>
  <Header />

  <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-4">Available Components</h1>

    <div v-if="user?.is_admin" class="my-4">
      <Link href="/components/create" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        + Add Component
      </Link>
    </div>
    
    <div v-if="isAuthenticated" class="mb-4">
      <input v-model="searchQuery" type="text" placeholder="Search components..." class="p-2 border rounded w-full" />
    </div>

    <ul class="space-y-4">
      <li v-for="component in filteredComponents" :key="component.id" class="p-4 bg-white rounded shadow hover:shadow-lg">
        <Link :href="`/components/${component.id}`" class="text-indigo-600 font-semibold" >
          {{ component.name }}
        </Link>
      </li>
    </ul>

    <p v-if="filteredComponents.length === 0" class="text-gray-500 mt-4">
      No components found.
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from '@/lib/axios';
import Header from '@/components/Header.vue'
import { user, isAuthenticated } from '@/stores/auth'

interface Component {
  id: number
  name: string
}

const allComponents = ref<Component[]>([])
const searchQuery = ref('')

const fetchComponents = async () => {
  try {
    const response = await axios.get('/api/components')
    allComponents.value = response.data.components ?? response.data
  } catch (error) {
    console.error('Failed to fetch components:', error)
  }
}

onMounted(fetchComponents)

const filteredComponents = computed(() => {
  if (!isAuthenticated.value || !searchQuery.value) {
    return allComponents.value
  }

  return allComponents.value.filter((component) =>
    component.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})
</script>
