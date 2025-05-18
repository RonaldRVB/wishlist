<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout })

document.title = "Wishlist - Utilisateurs";

const form = useForm({
    name: '',
    email: '',
    salutation_id: '',
    status_user_id: '',
    role: 'user',
    password: '',
})

const submit = () => {
    form.post(route('users.store'))
}

defineProps({
    salutations: Array,
    statuses: Array,
})
</script>

<template>

    <Head title="Wishlist - Utilisateurs" />
    <div class="w-full min-h-screen bg-[#E3EFFD] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <!-- Titre + bouton retour -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Ajouter un utilisateur</h1>

                <button @click="router.visit(route('users.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    Retour
                </button>
            </div>
        </div>

        <div class="max-w-4xl w-full bg-white p-6 rounded-xl shadow border border-blue-300">
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nom</label>
                    <input v-model="form.name" type="text" class="w-full rounded border-gray-300" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                    <input v-model="form.email" type="email" class="w-full rounded border-gray-300" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Salutation</label>
                    <select v-model="form.salutation_id" class="w-full rounded border-gray-300">
                        <option v-for="s in salutations" :key="s.id" :value="s.id">{{ s.salutation_value }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Statut</label>
                    <select v-model="form.status_user_id" class="w-full rounded border-gray-300">
                        <option v-for="s in statuses" :key="s.id" :value="s.id">{{ s.status_value }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Rôle</label>
                    <select v-model="form.role" class="w-full rounded border-gray-300">
                        <option value="user">Utilisateur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Mot de passe provisoire</label>
                    <input v-model="form.password" type="password" class="w-full rounded border-gray-300" />
                </div>


                <div class="text-right space-x-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
