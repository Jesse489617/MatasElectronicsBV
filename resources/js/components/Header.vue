<script setup>
import { Link } from '@inertiajs/vue3';
import { isAuthenticated, user, logout } from '@/stores/auth';
</script>

<template>
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-900"><Link href="/">Matas Electronics B.V.</Link></h1>

      <div v-if="user" class="flex items-center gap-4">
        <Link href="/history" class="text-indigo-600 hover:underline">
          History
        </Link>

        <span class="text-gray-600">Hi, {{ user.name }}</span>
        <button @click="logout" class="text-red-600 hover:underline">
          Logout
        </button>
      </div>

      <div v-else class="flex gap-4">
        <Link href="/login" class="text-indigo-600 hover:underline">Login</Link>
        <Link href="/register" class="text-indigo-600 hover:underline">Register</Link>
      </div>
    </div>
  </header>
</template>
