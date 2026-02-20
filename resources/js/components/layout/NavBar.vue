<template>
    <nav class="bg-gray-800">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <Link :href="route('home')" class="shrink-0 text-gray-100 hover:text-white">
                        <LogoIcon class="size-12"/>
                    </Link>
                    <div class="md:block">
                        <div class="ml-6 flex items-center space-x-3">
                            <NavBarMenuItem v-for="(items, heading) in menu" :key="heading" :items="items">
                                {{ heading }}
                            </NavBarMenuItem>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<!--    <header class="bg-gray-800 shadow">-->
<!--        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">-->
<!--            <div class="flex items-center gap-4">-->
<!--                &lt;!&ndash; Logo &ndash;&gt;-->
<!--                <Link href="/" class="flex items-center">-->
<!--                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="currentColor" class="h-12 w-12 text-white">-->
<!--                        <path-->
<!--                            d="M9.352 6.207c-.047.106-1.754 4.89-3.801 10.645-2.043 5.746-3.746 10.503-3.766 10.562-.027.066-.07.098-.101.078-.133-.078-.79.125-1.055.324-.352.27-.629.809-.629 1.23 0 .434.266.974.598 1.212.363.27.648.347 1.129.312.488-.03.843-.238 1.156-.687.383-.547.293-1.45-.2-1.934l-.222-.222 3.727-10.442L9.91 6.852l14.98.02 14.981.023v-.707l-5.273-.04c-2.903-.023-9.754-.062-15.227-.09l-9.95-.038zm-7.5 22.461c.285.281.195.742-.165.871a.51.51 0 01-.609-.203c-.355-.504.336-1.11.774-.668zm0 0"-->
<!--                        />-->
<!--                        <path-->
<!--                            d="M14.742 7.969c0 .207-6.176 18.265-6.312 18.433-.028.04-.223.09-.43.11-1.488.133-2.55 1.773-2.125 3.265.469 1.633 2.379 2.407 3.832 1.555.398-.234.86-.754 1.059-1.187.226-.497.28-1.332.109-1.84-.133-.414-.484-.97-.758-1.196l-.195-.16 3.047-8.82 3.039-8.813 9.023.02c4.969.008 10.364.035 12 .059L40 9.434V7.87l-3.844-.039c-2.117-.02-7.804-.055-12.629-.066l-8.785-.032zM8.715 28.637c.09.07.18.199.195.285.11.566-.617.89-.95.414-.155-.219-.124-.426.087-.64.219-.223.441-.243.668-.06zm0 0"-->
<!--                        />-->
<!--                        <path-->
<!--                            d="M21.04 11.39c0 .06-.981 2.993-2.185 6.52-1.199 3.524-2.308 6.793-2.472 7.258l-.285.86-.371.128c-1.215.45-1.993 1.473-2.07 2.739-.196 2.921 3.39 4.488 5.382 2.351.637-.676.856-1.312.816-2.312-.02-.543-.05-.72-.199-1.043l-.183-.383 2.164-6.262 2.16-6.258 8.105.024 8.098.027v-3.676h-6.836c-3.77 0-8.035-.02-9.48-.047-2.461-.039-2.645-.03-2.645.075zm-3.942 17.247a.49.49 0 01.148.64.53.53 0 01-.91.059c-.152-.219-.121-.426.086-.64.223-.223.441-.243.676-.06zm0 0"-->
<!--                        />-->
<!--                        <path-->
<!--                            d="M27.148 17.215c-.03.082-.628 1.902-1.332 4.031l-1.277 3.879-.41.29a4.73 4.73 0 00-1.984 3.831c0 .805.132 1.39.48 2.086.484.984 1.18 1.676 2.148 2.14.872.41 1.305.508 2.22.477 1.292-.047 2.234-.457 3.175-1.383.437-.43.578-.632.824-1.136.418-.84.528-1.305.528-2.176 0-1.098-.297-2-.954-2.863l-.3-.399.43-1.578.433-1.578 4.332.047 4.324.039.047-.336c.02-.191.04-1.496.04-2.898v-2.543h-5.15c-2.84 0-5.683-.02-6.331-.047l-1.176-.04zm.008 11.488c.16.168.188.23.157.402-.079.403-.364.563-.727.407-.383-.157-.441-.617-.113-.875.234-.184.457-.164.683.066zm0 0"-->
<!--                        />-->
<!--                    </svg>-->
<!--                </Link>-->

<!--                &lt;!&ndash; Navigation Links &ndash;&gt;-->
<!--                <Link href="/components" class="rounded-md px-3 py-2 text-sm font-medium text-white hover:bg-gray-700"> Components </Link>-->
<!--                <Link href="/assemblies" class="rounded-md px-3 py-2 text-sm font-medium text-white hover:bg-gray-700"> Assemblies </Link>-->
<!--            </div>-->

<!--            <div v-if="user" class="relative flex items-center gap-4">-->
<!--                &lt;!&ndash; Cart Icon &ndash;&gt;-->
<!--                <Link href="/cart" class="flex h-10 w-10 items-center justify-center rounded-md text-white hover:bg-gray-800" title="Your Cart">-->
<!--                    <ShoppingCartIcon class="h-6 w-6" />-->
<!--                </Link>-->

<!--                &lt;!&ndash; Avatar &ndash;&gt;-->
<!--                <img-->
<!--                    id="user-avatar"-->
<!--                    @click="toggleMenu"-->
<!--                    class="h-10 w-10 cursor-pointer rounded-full object-cover"-->
<!--                    :src="`https://intranet.matas.nl/avatars/420?size=60`"-->
<!--                    alt="User Avatar"-->
<!--                />-->

<!--                &lt;!&ndash; Dropdown Menu &ndash;&gt;-->
<!--                <div-->
<!--                    v-if="showMenu"-->
<!--                    ref="user-dropdown"-->
<!--                    class="absolute top-full right-0 z-20 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg"-->
<!--                >-->
<!--                    <div class="py-1">-->
<!--                        <Link href="/history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"> History </Link>-->
<!--                    </div>-->
<!--                    <div class="py-1">-->
<!--                        <button @click="handleLogout" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Logout</button>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--            <div v-else class="flex gap-4">-->
<!--                <Link href="/login" class="text-white hover:underline">Login</Link>-->
<!--                <Link href="/register" class="text-white hover:underline">Register</Link>-->
<!--            </div>-->
<!--        </div>-->
<!--    </header>-->
</template>

<script setup lang="ts">
    //import { ShoppingCartIcon } from '@heroicons/vue/24/solid';
    import { Link } from '@inertiajs/vue3';
    import { ref, onMounted, onBeforeUnmount } from 'vue';
    import LogoIcon from '@/components/elements/icons/LogoIcon.vue';
    import NavBarMenuItem from '@/components/layout/NavBarMenuItem.vue';
    //import { user, logout } from '@/stores/auth';

    defineOptions({
        name: 'NavBar',
    })

    const menu = {
        Products: [
            { title: 'Assemblies', url: route('assemblies.index') },
            { title: 'Components', url: route('components.index') },
        ],
    }

    const showMenu = ref<boolean>(false);

    //const userDropdownRef = useTemplateRef('user-dropdown')

    // const toggleMenu = (): void => {
    //     showMenu.value = !showMenu.value
    // }
    //
    // function handleLogout() {
    //     logout();
    //     showMenu.value = false;
    // }

    // Close dropdown when clicking outside
    function handleClickOutside(event: MouseEvent) {
        const menu = document.getElementById('user-dropdown'); // is stuk, vanwege reference
        const avatar = document.getElementById('user-avatar');
        if (menu && avatar && !menu.contains(event.target as Node) && !avatar.contains(event.target as Node)) {
            showMenu.value = false;
        }
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside);
    });
</script>
