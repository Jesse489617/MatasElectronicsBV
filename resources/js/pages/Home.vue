<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';
    import Nav from '@/components/Nav.vue';
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Nav />
        <!-- Main -->
        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold">Explore our products</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-lg bg-white p-6 shadow transition hover:shadow-lg">
                    <h3 class="mb-2 text-xl font-semibold">Individual Components</h3>
                    <p class="mb-4 text-gray-600">View all available individual electronic components.</p>
                    <Link href="/components" class="text-indigo-600 hover:underline"> View Components → </Link>
                </div>

                <div class="rounded-lg bg-white p-6 shadow transition hover:shadow-lg">
                    <h3 class="mb-2 text-xl font-semibold">Assemblies</h3>
                    <p class="mb-4 text-gray-600">View assemblies and see which components belong to each.</p>
                    <Link href="/assemblies" class="text-indigo-600 hover:underline"> View Assemblies → </Link>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped></style>
