<script setup>
import { router } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps({
    users: Array,
})
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose">
            <h1 class="text-3xl font-bold mb-6 text-blue-900">Liste des utilisateurs</h1>
        </div>

        <div class="mb-6 w-full flex justify-center">
            <button class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                @click="router.visit(route('users.create'))">
                + Créer un utilisateur
            </button>
        </div>


        <div class="max-w-7xl w-full">
            <table class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full">
                <thead class="bg-teal-200 text-black">
                    <tr>
                        <th class="text-left px-4 py-2">Salutation</th>
                        <th class="text-left px-4 py-2">Nom</th>
                        <th class="text-left px-4 py-2">Email</th>
                        <th class="text-left px-4 py-2">Statut</th>
                        <th class="text-left px-4 py-2">Rôle</th>
                        <th class="text-left px-4 py-2">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" class="border-t border-green-500">
                        <td class="px-4 py-2 font-bold">{{ user.salutation?.salutation_value }}</td>
                        <td class="px-4 py-2 font-bold">{{ user.name }}</td>
                        <td class="px-4 py-2 font-bold">{{ user.email }}</td>
                        <td class="px-4 py-2 font-bold">{{ user.status_user?.status_value }}</td>
                        <td class="px-4 py-2 font-bold">{{ user.role }}</td>
                        <td class="px-4 py-2">
                            <button @click="router.visit(route('users.edit', user.id))"
                                class="text-blue-700 hover:text-[#F87171]" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" viewBox="0 0 20 20"
                                    fill="currentColor">
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
</template>
