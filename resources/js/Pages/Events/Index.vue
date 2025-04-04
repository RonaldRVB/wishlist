<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineOptions({ layout: AppLayout })

const props = defineProps({
    events: Array,
})
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Liste des événements</h1>

                <button @click="router.visit(route('events.create'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    + Nouvel événement
                </button>
            </div>

            <div class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full">
                <table class="w-full">
                    <thead class="bg-teal-200 text-black">
                        <tr>
                            <th class="text-left px-4 py-2">Titre</th>
                            <th class="text-left px-4 py-2">Date</th>
                            <th class="text-left px-4 py-2">Slug</th>
                            <th class="text-left px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="event in events" :key="event.id" class="border-t border-green-500">
                            <td class="px-4 py-2 font-bold">{{ event.title }}</td>
                            <td class="px-4 py-2 font-bold">{{ new Date(event.event_date).toLocaleDateString('fr-FR') }}
                            </td>
                            <td class="px-4 py-2 font-bold italic text-gray-600">{{ event.slug }}</td>
                            <td class="px-4 py-2 flex items-center space-x-2">
                                <button @click="router.visit(route('events.show', event.id))"
                                    class="text-blue-700 hover:text-teal-900" title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 5c-7.633 0-10 7-10 7s2.367 7 10 7 10-7 10-7-2.367-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                                    </svg>
                                </button>

                                <button @click="router.visit(route('events.edit', event.id))"
                                    class="text-blue-700 hover:text-[#F87171]" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM4 16a1 1 0 100 2h12a1 1 0 100-2H4z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
