<template>
  <Header />
  <div class="max-w-3xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Create New Assembly</h1>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1">Assembly Name</label>
        <input v-model="name" type="text" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1">Select Components</label>
        <div v-for="component in components" :key="component.id" class="flex items-center mb-1">
          <input type="checkbox" :value="component.id" v-model="selectedComponents" />
          <span class="ml-2">{{ component.name }} — €{{ component.price }}</span>
        </div>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Total Component Price (€)</label>
        <input type="number" :value="totalPrice" class="w-full p-2 border rounded bg-gray-100" readonly />
      </div>

      <div class="mb-4">
        <label class="block mb-1">Assembly Price (€)</label>
        <input v-model.number="price" type="number" class="w-full p-2 border rounded" :min="totalPrice" />
        <p class="text-gray-500 text-sm mt-1">
          Minimum price based on selected components: €{{ totalPrice }}
        </p>
      </div>

      <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        Create Assembly
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted, computed } from 'vue'
  import axios from 'axios'
  import Header from '@/components/Header.vue'
  import { router } from '@inertiajs/vue3'

  const name = ref('')
  const price = ref<number | null>(null)
  const components = ref<any[]>([])
  const selectedComponents = ref<number[]>([])

  onMounted(async () => {
    try {
      const res = await axios.get('/api/components')
      components.value = res.data.components ?? res.data
    } catch (err) {
      console.error('Failed to load components', err)
    }
  })

  const totalPrice = computed(() => {
    return selectedComponents.value.reduce((sum, compId) => {
      const comp = components.value.find(c => c.id === compId)
      return sum + (comp?.price ?? 0)
    }, 0)
  })

  const submit = async () => {
    if (!name.value) {
      alert('Assembly name is required')
      return
    }

    if (!selectedComponents.value.length) {
      alert('Select at least one component')
      return
    }
    
    if (price.value !== null && price.value < totalPrice.value) {
      const confirmLower = confirm(
        `The assembly price (€${price.value}) is lower than the total component price (€${totalPrice.value}). Proceed?`
      )
      if (!confirmLower) return
    }

    try {
      await axios.post(
        '/api/assemblies/create',
        {
          name: name.value,
          price: price.value ?? totalPrice.value,
          components: selectedComponents.value
        },
        { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } }
      )

      router.visit('/assemblies')
    } catch (err) {
      console.error(err)
      alert('Failed to create assembly.')
    }
  }
</script>
