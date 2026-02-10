<template>
  <Header />

  <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div v-if="assembly" class="p-6 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-2">{{ assembly.name }}</h1>

      <p class="text-gray-600 mb-2">Assembly ID: {{ assembly.id }}</p>
      <p class="text-lg font-semibold mb-4">
        Price: €{{ assembly.price }}
      </p>

      <p class="text-lg font-semibold mt-4 mb-2">
        Components in this Assembly:
      </p>
      <ul class="list-disc list-inside mb-6">
        <li v-for="comp in assembly.components" :key="comp.id">
          {{ comp.name }} ({{ comp.type }})
        </li>
      </ul>

      <div v-if="isAuthenticated" class="border-t pt-4">
        <h2 class="text-lg font-semibold mb-2">Buy this assembly</h2>

        <div class="flex items-center gap-3">
          <input type="number" min="1" v-model.number="quantity" class="w-20 p-2 border rounded"
          />

          <button @click="buyAssembly" :disabled="isBuying" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 disabled:opacity-50" >
            {{ isBuying ? 'Buying...' : 'Buy' }}
          </button>
        </div>
      </div>

      <div v-else class="border-t pt-4 text-gray-600">
        <p>
          You must be logged in to purchase this assembly.
        </p>
      </div>
    </div>

    <div v-else class="p-6 text-gray-500">
      Loading assembly...
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { defineProps } from 'vue'
import axios from '@/lib/axios';
import Header from '@/components/Header.vue'
import { isAuthenticated } from '@/stores/auth'

interface Component {
  id: number
  name: string
  type: string
  price: number
}

interface Assembly {
  id: number
  name: string
  price: number
  components: Component[]
}

const props = defineProps<{ id: string }>()

const assembly = ref<Assembly | null>(null)
const quantity = ref(1)
const isBuying = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get(`/api/assemblies/${props.id}`)
    assembly.value = response.data.assembly
  } catch (err) {
    console.error('Error fetching assembly:', err)
  }
})

const buyAssembly = async () => {
  if (!assembly.value || quantity.value < 1) return

  isBuying.value = true

  try {
    const response = await axios.post(
      '/api/assemblies/buy',
      {
        assembly_id: assembly.value.id,
        quantity: quantity.value,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      }
    )

    alert(response.data.message)
    quantity.value = 1
  } catch (error: any) {
    console.error(error)
    alert(error.response?.data?.message || 'Purchase failed')
  } finally {
    isBuying.value = false
  }
}
</script>
