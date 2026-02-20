<template>
    <div class="h-full">
        <NavBar />
        <Notification />

        <slot />
    </div>
</template>

<script setup lang="ts">
    import NavBar from '@/components/layout/NavBar.vue';
    import Notification from '@/components/layout/Notification.vue';

    defineOptions({
        name: 'DefaultLayout',
    })
</script>
