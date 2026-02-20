<template>
    <div :class="[notificationStyling.background, 'pointer-event-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg']">
        <div class="p-4">
            <div class="flex items-start">
                <div class="shrink-0">
                    <component :is="notificationStyling.icon" :class="[notificationStyling.iconStyle, 'size-6 stroke-2']" />
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p :class="[notificationStyling.title, 'text-sm font-medium leading-5']">
                        {{ notification.title }}
                    </p>
                    <p :class="[notificationStyling.message, 'mt-1 text-sm leading-5']" v-html="notification.message" />
                </div>
                <div class="ml-4 flex shrink-0">
                    <button type="button"
                            :class="[notificationStyling.button, 'inline-flex transition focus-outline-hidden']"
                            @click="close">
                        <span class="sr-only">Close</span>
                        <XMarkIcon class="size-5" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { XMarkIcon } from '@heroicons/vue/20/solid'
    import { CheckCircleIcon, ExclamationCircleIcon, ExclamationTriangleIcon} from '@heroicons/vue/24/outline'
    import { computed } from 'vue';

    defineOptions({
        name: 'Notification',
    })

    const props = defineProps({
        notification: {
            type: Object,
            required: true,
        }
    })

    const emit = defineEmits(['close'])

    const close = () => emit('close', props.notification.id)

    const notificationStyling = computed(() => {
        switch (props.notification.type) {
            case 'info':
                return {
                    background: 'bg-blue-100',
                    icon: ExclamationCircleIcon,
                    iconStyle: 'text-blue-400',
                    title: 'text-blue-800',
                    message: 'text-blue-700',
                    button: 'text-blue-400 focus:text-blue-500',
                }
            case 'warning':
                return {
                    background: 'bg-yellow-100',
                    icon: ExclamationCircleIcon,
                    iconStyle: 'text-yellow-400',
                    title: 'text-yellow-800',
                    message: 'text-yellow-700',
                    button: 'text-yellow-400 focus:text-yellow-500',
                }
            case 'error':
                return {
                    background: 'bg-red-100',
                    icon: ExclamationTriangleIcon,
                    iconStyle: 'text-red-400',
                    title: 'text-red-800',
                    message: 'text-red-700',
                    button: 'text-red-400 focus:text-red-500',
                }
            default:
                return {
                    background: 'bg-green-100',
                    icon: CheckCircleIcon,
                    iconStyle: 'text-green-400',
                    title: 'text-green-800',
                    message: 'text-green-700',
                    button: 'text-green-400 focus:text-green-500',
                }
        }
    })
</script>
