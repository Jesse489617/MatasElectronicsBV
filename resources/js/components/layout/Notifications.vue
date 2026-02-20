<template>
    <div aria-live="assertive" class="pointer-events-none fixed inset-0 z-20 flex items-end px-4 py-6 sm:items-start sm:p-6">
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <transition-group enter-active-class="transform ease-out duration-300 transition"
                              enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                              enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                              leave-active-class="transition ease-in duration-100"
                              leave-from-class="opacity-100"
                              leave-to-class="opacity-0">
                <Notification v-for="notification in notifications"
                              :key="notification.id"
                              :notification="notification"
                              @close="(id) => removeNotification(id)" />
            </transition-group>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { router, usePage } from '@inertiajs/vue3'
    import { computed, watch } from 'vue'
    import Notification from '@/components/layout/Notification.vue'
    import useNotifications from '@/composables/useNotifications'

    defineOptions({
        name: 'Notifications',
    })

    const notification = computed(() => (usePage().props as any).flash?.notification)
    const { addNotification, removeNotification, notifications} = useNotifications()

    watch(notification, (newVal) => {
        if(newVal) {
            addNotification(newVal)
            router.reload({only: ['flash']})
        }
    })
</script>



