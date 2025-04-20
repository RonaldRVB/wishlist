<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
defineOptions({ layout: AppLayout })

const props = defineProps({
    gifts: Array
})
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="max-w-7xl w-full">
            <h1 class="text-3xl font-bold text-blue-900 mb-6">Mes cadeaux</h1>

            <div class="bg-[#E3EFFD] shadow rounded-xl overflow-hidden border border-blue-300">
                <table class="w-full">
                    <thead class="bg-teal-200 text-black">
                        <tr>
                            <th class="text-left px-4 py-2">Nom</th>
                            <th class="text-left px-4 py-2">Description</th>
                            <th class="text-left px-4 py-2">Wishlist</th>
                            <th class="text-left px-4 py-2">Événements liés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="gift in gifts" :key="gift.id" class="border-t border-green-500">
                            <td class="px-4 py-2 font-bold">{{ gift.name }}</td>
                            <td class="px-4 py-2">{{ gift.description || '—' }}</td>

                            <td class="px-4 py-2">
                                <ul class="space-y-1">
                                    <li v-for="wishlist in gift.wishlists" :key="wishlist.id">
                                        <div class="text-blue-800 font-semibold">{{ wishlist.title }}</div>
                                    </li>
                                </ul>
                            </td>

                            <td class="px-4 py-2">
                                <ul class="space-y-1">
                                    <li v-for="wishlist in gift.wishlists" :key="wishlist.id">
                                        <div v-if="wishlist.events.length > 0" class="text-sm text-gray-700">
                                            <span v-for="(event, index) in wishlist.events" :key="event.id">
                                                {{ event.title }}<span v-if="index < wishlist.events.length - 1">,
                                                </span>
                                            </span>
                                        </div>
                                        <div v-else class="text-sm italic text-gray-500">
                                            (non liée à un événement)
                                        </div>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</template>
