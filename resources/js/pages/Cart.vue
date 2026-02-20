<template>
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-2xl font-bold">Your Cart</h1>

        <div v-if="items.length === 0" class="text-gray-500">Your cart is empty.</div>

        <div v-else class="space-y-6">
            <div v-for="item in items" :key="item.id" class="rounded bg-white p-6 shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">
                            {{ getItemName(item) }}
                        </h2>

                        <p class="text-sm text-gray-500">Quantity: {{ item.quantity }}</p>
                    </div>

                    <div class="text-right">
                        <p class="font-semibold">€{{ getItemPrice(item) * item.quantity }}</p>

                        <button @click="removeItem(item.id)" class="mt-2 text-sm text-red-500 hover:underline">Remove</button>
                    </div>
                </div>
            </div>

            <!-- TOTAL -->
            <div class="flex items-center justify-between border-t pt-6">
                <div class="text-xl font-semibold">Total: €{{ totalPrice }}</div>

                <button
                    @click="checkout"
                    :disabled="isCheckingOut"
                    class="rounded bg-black px-6 py-3 font-medium text-white hover:bg-gray-800 disabled:opacity-50"
                >
                    {{ isCheckingOut ? 'Processing...' : 'Checkout' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { checkoutCart } from '@/lib/cart/checkoutCart';
import { getCart } from '@/lib/cart/getCart';
import { removeCartItem } from '@/lib/cart/removeCartItem';

const items = ref<any[]>([]);
const isCheckingOut = ref(false);

onMounted(async () => {
    await loadCart();
});

const loadCart = async () => {
    try {
        const data = await getCart();
        items.value = data.items ?? [];
    } catch (err) {
        console.error('Failed to load cart:', err);
    }
};

const getItemName = (item: any) => {
    if (item.component) return `[Component] ${item.component.name}`;
    if (item.custom_assembly) return `[Custom Assembly] ${item.custom_assembly.name}`;
    if (item.assembly) return `[Assembly] ${item.assembly.name}`;
    return '[Unknown] Item';
};

const getItemPrice = (item: any) => {
    if (item.assembly) return item.assembly.price;
    if (item.component) return item.component.price;
    if (item.custom_assembly) return item.custom_assembly.price;
    return 0;
};

const totalPrice = computed(() => {
    return items.value.reduce((sum, item) => {
        return sum + getItemPrice(item) * item.quantity;
    }, 0);
});

const removeItem = async (id: number) => {
    try {
        await removeCartItem(id);
        items.value = items.value.filter((i) => i.id !== id);
    } catch (err) {
        console.error('Failed to remove item', err);
    }
};

const checkout = async () => {
    isCheckingOut.value = true;

    try {
        await checkoutCart();
        alert('Purchase successful!');
        items.value = [];
    } catch (err: any) {
        console.error(err);
        alert(err.response?.data?.message || 'Checkout failed');
    } finally {
        isCheckingOut.value = false;
    }
};
</script>
