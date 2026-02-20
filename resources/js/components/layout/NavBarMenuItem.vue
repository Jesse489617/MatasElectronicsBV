<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton class="bg-gray-900 hover:bg-gray-700 rounded-md px-3 py-2 text-sm font-medium text-white focus:outline-hidden">
                <slot />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems class="absolute left-0 z-50 mt-2 w-64 origin-top-left rounded-md bg-white shadow-lg outline-1 outline-black/5">
                <div class="py-1">
                    <MenuItem v-for="item in items" :key="item.url" v-slot="{ active, close }">
                        <Link
                            :href="item.url"
                            :on-before="() => close()"
                            prefetch
                            :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                        >
                            {{ item.title }}
                        </Link>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
    name: 'NavBarMenuItem',
});

interface NavItem {
    url: string;
    title: string;
}

defineProps<{
    items: NavItem[];
}>();
</script>
