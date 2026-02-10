<script setup>
    import { Link } from '@inertiajs/vue3';
    import Header from '@/components/Header.vue';
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <Header />

    <!-- Main -->
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6">Explore our products</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
          <h3 class="text-xl font-semibold mb-2">Individual Components</h3>
          <p class="text-gray-600 mb-4">View all available individual electronic components.</p>
          <Link href="/components" class="text-indigo-600 hover:underline"> View Components → </Link>
        </div>

        <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
          <h3 class="text-xl font-semibold mb-2">Assemblies</h3>
          <p class="text-gray-600 mb-4">View assemblies and see which components belong to each.</p>
          <Link href="/assemblies" class="text-indigo-600 hover:underline"> View Assemblies → </Link>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
</style>
