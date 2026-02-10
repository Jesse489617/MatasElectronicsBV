<template>
  <Header />

  <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">Purchase History</h1>

    <div class="flex flex-col sm:flex-row gap-4 mb-6">
      <input
        v-model="search"
        type="text"
        placeholder="Search assemblies or components..."
        class="p-2 border rounded w-full"
      />

      <select v-model="sortBy" class="p-2 border rounded w-48">
        <option value="date">Sort by date</option>
        <option value="name">Sort by name</option>
        <option value="price">Sort by price</option>
      </select>
    </div>

    <div
      v-for="item in filteredHistory"
      :key="item.id"
      class="mb-6 p-6 bg-white rounded shadow"
    >
      <div class="flex justify-between items-start">
        <div>
          <h2 class="text-xl font-semibold">
            {{ item.assembly.name }}
          </h2>
          <p class="text-sm text-gray-500">
            Purchased on {{ formatDate(item.created_at) }}
          </p>
        </div>

        <div class="text-right font-semibold">
          €{{ item.assembly.price }}
        </div>
      </div>

      <div class="mt-4">
        <p class="font-semibold mb-1">Components</p>
        <ul class="list-disc list-inside">
          <li
            v-for="component in item.assembly.components"
            :key="component.id"
          >
            {{ component.name }} — €{{ component.price }}
          </li>
        </ul>
      </div>
    </div>

    <p
      v-if="filteredHistory.length === 0"
      class="text-gray-500 text-center"
    >
      No purchases found.
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Header from '@/components/Header.vue'
import { isAuthenticated, fetchUser } from '@/stores/auth'

const history = ref<any[]>([])
const search = ref('')
const sortBy = ref<'date' | 'name' | 'price'>('date')

onMounted(async () => {
  await fetchUser()

  if (!isAuthenticated.value) {
    router.visit('/login')
    return
  }

  try {
    const response = await axios.get('/api/history', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })

    history.value = response.data.history
  } catch (error) {
    console.error('Failed to load history:', error)
  }
})

const filteredHistory = computed(() => {
  let result = [...history.value]

  // Search (assembly + components)
  if (search.value) {
    const q = search.value.toLowerCase()
    result = result.filter(item =>
      item.assembly.name.toLowerCase().includes(q) ||
      item.assembly.components.some((c: any) =>
        c.name.toLowerCase().includes(q)
      )
    )
  }

  if (sortBy.value === 'name') {
    result.sort((a, b) =>
      a.assembly.name.localeCompare(b.assembly.name)
    )
  } else if (sortBy.value === 'price') {
    result.sort((a, b) =>
      a.assembly.price - b.assembly.price
    )
  } else {
    result.sort((a, b) =>
      new Date(b.created_at).getTime() -
      new Date(a.created_at).getTime()
    )
  }

  return result
})

const formatDate = (date: string) =>
  new Date(date).toLocaleDateString()
</script>
