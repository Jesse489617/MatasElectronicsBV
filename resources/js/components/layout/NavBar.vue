<template>
    <nav class="bg-gray-800">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <Link :href="route('home')" class="shrink-0 text-gray-100 hover:text-white">
                        <LogoIcon class="size-12" />
                    </Link>
                    <div class="md:block">
                        <div class="ml-6 flex items-center space-x-3">
                            <NavBarMenuItem v-for="(items, heading) in menu" :key="heading" :items="items">
                                {{ heading }}
                            </NavBarMenuItem>
                        </div>
                    </div>
                </div>
                <div v-if="user" class="md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <div class="items-center xl:flex">
                            <NavIcon />
                        </div>
                        <Menu as="div" class="relative inline-block text-left">
                            <div class="-mr-2">
                                <MenuButton
                                    v-slot="{ open: menuOpen }"
                                    class="group inline-flex w-full justify-center gap-x-1.5 px-3 py-2 text-sm font-semibold text-gray-900" >
                                    <img
                                        :class="[
                                            { 'ring-2 ring-white': menuOpen },
                                            'size-8 rounded-full object-cover group-hover:ring-2 group-hover:ring-white group-focus:ring-2 group-focus:ring-white dark:group-hover:ring-white/40 dark:group-focus:ring-white/40',]"
                                        :src="`https://intranet.matas.nl/avatars/420?size=60`"
                                        alt="employee"
                                    />
                                </MenuButton>
                            </div>

                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95" >
                                <MenuItems class="absolute right-0 z-10 mt-0 w-48 origin-top-right divide-y divide-gray-100 rounded-md bg-white">
                                    <div class="py-1">
                                        <MenuItem v-slot="{ active, close }">
                                            <Link
                                                :href="route('history')"
                                                :on-success="() => close()"
                                                :class="[{ 'bg-gray-100': active }, 'block px-4 py-2 text-sm text-gray-700']"
                                            >
                                                History
                                            </Link>
                                        </MenuItem>
                                    </div>
                                    <div class="py-1">
                                        <MenuItem v-slot="{ active }">
                                            <Button
                                                @click="handleLogout"
                                                :class="[{ 'bg-gray-100': active }, 'block w-full px-4 py-2 text-left text-sm text-gray-700']"
                                            >
                                                Logout
                                            </Button>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
                <div v-else class="md:block">
                    <div class="ml-4 flex items-center md:ml-6 gap-4">
                        <Link
                            :href="route('login')"
                            class="text-sm font-semibold text-gray-900 hover:text-gray-700">
                            Login
                        </Link>
                        <Link
                            :href="route('register')"
                            class="rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                            Register
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup lang="ts">
    import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
    import { Link, router } from '@inertiajs/vue3'
    import LogoIcon from '@/components/elements/icons/LogoIcon.vue'
    import NavBarMenuItem from '@/components/layout/NavBarMenuItem.vue'
    import NavIcon from '@/components/layout/NavIcon.vue'
    import { logout, user } from '@/stores/auth'

    defineOptions({
        name: 'NavBar',
    });

    const menu = {
        Products: [
            { title: 'Assemblies', url: route('assemblies.index') },
            { title: 'Components', url: route('components.index') },
        ],
    };

    function handleLogout() {
        logout().finally(() => {
            router.flushAll()
        });
    }
</script>
