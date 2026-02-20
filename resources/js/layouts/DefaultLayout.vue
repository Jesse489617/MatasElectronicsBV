<template>
    <div class="h-full">
        <NavBar />
        <Notifications />

        <slot />
    </div>
</template>

<script setup lang="ts">
    import NavBar from '@/components/layout/NavBar.vue';
    import Notifications from '@/components/layout/Notifications.vue';

    defineOptions({
        name: 'DefaultLayout',
    })
</script>
