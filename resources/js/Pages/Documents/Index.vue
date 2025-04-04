<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps({
    documents: Array
})
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <h1 class="text-3xl font-bold mb-6 text-blue-900">Mentions légales - Administration</h1>

        <div class="mb-4">
            <button @click="router.visit(route('documents.create'))"
                class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                + Nouveau document
            </button>
        </div>

        <div class="max-w-7xl w-full">
            <table class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full">
                <thead class="bg-teal-200 text-black">
                    <tr>
                        <th class="text-left px-4 py-2">Titre</th>
                        <th class="text-left px-4 py-2">Version</th>
                        <th class="text-left px-4 py-2">Actif</th>
                        <th class="text-left px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="doc in documents" :key="doc.id" class="border-t border-green-500">
                        <td class="px-4 py-2 font-bold">{{ doc.title }}</td>
                        <td class="px-4 py-2 font-bold">{{ doc.version }}</td>
                        <td class="px-4 py-2 font-bold">
                            <span :class="doc.is_active ? 'text-green-700' : 'text-gray-500'">
                                {{ doc.is_active ? 'Oui' : 'Non' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-3 justify-center">
                                <!-- Bouton Voir -->
                                <button @click="router.visit(route('documents.show', doc.id))" title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-blue-700 hover:text-[#F87171]" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 5c-7.633 0-10 7-10 7s2.367 7 10 7 10-7 10-7-2.367-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                                    </svg>
                                </button>

                                <!-- Bouton Modifier -->
                                <button @click="router.visit(route('documents.edit', doc.id))" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-blue-700 hover:text-[#F87171]" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM4 16a1 1 0 100 2h12a1 1 0 100-2H4z" />
                                    </svg>
                                </button>
                            </div>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
