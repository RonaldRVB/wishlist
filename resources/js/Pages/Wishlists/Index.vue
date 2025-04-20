<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
defineOptions({ layout: AppLayout })

const props = defineProps({
    wishlists: Array
})
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="max-w-4xl w-full">
            <h1 class="text-3xl font-bold text-blue-900 mb-6">Mes wishlists 🎁</h1>

            <div class="bg-[#E3EFFD] shadow rounded-xl p-6 space-y-4">
                <div v-for="wishlist in wishlists" :key="wishlist.id" class="border-b pb-4">
                    <h2 class="text-xl font-semibold text-blue-800">
                        {{ wishlist.title }}
                    </h2>
                    <p class="text-gray-700">{{ wishlist.description || '— Aucune description —' }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
