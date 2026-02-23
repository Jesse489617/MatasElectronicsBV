<template>
    <Link
        :href="route('cart')"
        class="p-1 ml-3 mr-2 inline-block relative border-2 border-transparent rounded-full focus:outline-none text-gray-100 hover:text-white" >
        <ShoppingCartIcon class="size-6" />
    </Link>
</template>

<script setup lang="ts">
    import { ShoppingCartIcon } from '@heroicons/vue/24/outline'
    import { Link } from '@inertiajs/vue3'

    defineOptions({
        name: 'NavIcon',
    })
</script>




