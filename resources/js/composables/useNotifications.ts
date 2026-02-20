import type { Ref } from 'vue';
import { ref } from 'vue';
import type { Notification } from '@/types';

interface UseNotificationsReturn {
    notifications: Ref<Notification[]>
    addNotification: (notification: Omit<Notification, 'id'>) => void
    removeNotification: (id: string) => void
    removeAllNotifications: () => void
    notifySuccess: (text: string, title?: string) => void
    notifyInfo: (text: string, title?: string) => void
    notifyWarning: (text: string, title?: string) => void
    notifyError: (text: string, title?: string) => void
}

const notifications = ref<Notification[]>([])
const defaultDuration: number = 3000

export default function useNotifications(): UseNotificationsReturn {
    const removeNotification = (id: string): void => {
        notifications.value = notifications.value.filter(notification => notification.id !== id )
    }

    const addNotification = (notification: Omit<Notification, 'id'>): void => {
        const id = typeof crypto !== "undefined" && typeof crypto.randomUUID === "function"
            ? crypto.randomUUID()
            : (Date.now().toString(36) + Math.random().toString(36).slice(2));

        notifications.value.push({
            id: id,
            ...notification,
        })

        setTimeout((): void => {
            removeNotification(id)
        }, notification.duration || defaultDuration)

        if (notifications.value.length > 5) {
            notifications.value = notifications.value.slice(1)
        }
    }

    const removeAllNotifications = (): void => {
        notifications.value = []
    }

    const notifySuccess = (text: string, title: string = 'Succeeded!'): void => {
        addNotification({
            type: 'success',
            title: title,
            message: text
        })
    }

    const notifyInfo = (text: string, title: string = 'Heads up!!'): void => {
        addNotification({
            type: 'success',
            title: title,
            message: text
        })
    }

    const notifyWarning = (text: string, title: string = 'Warning!'): void => {
        addNotification({
            type: 'success',
            title: title,
            message: text
        })
    }

    const notifyError = (text: string, title: string = 'Error!'): void => {
        addNotification({
            type: 'success',
            title: title,
            message: text
        })
    }

    return {
        notifications,
        addNotification,
        removeNotification,
        removeAllNotifications,
        notifySuccess,
        notifyInfo,
        notifyWarning,
        notifyError
    }
}


