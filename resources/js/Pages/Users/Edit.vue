<script setup>
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    user: Object,
    salutations: Array,
    statuses: Array,
})

// Formulaire réactif basé sur l'utilisateur reçu
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    salutation_id: props.user.salutation_id,
    status_user_id: props.user.status_user_id,
    role: props.user.role,
})


const submit = () => {
    form.put(route('users.update', props.user.id))
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex justify-center">
        <div class="bg-[#E3EFFD] p-6 rounded-xl shadow max-w-2xl w-full">
            <h1 class="text-2xl font-bold mb-6 text-center text-blue-900">Modifier l’utilisateur</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block font-semibold mb-1">Nom</label>
                    <input v-model="form.name" type="text" class="w-full rounded border border-gray-300 p-2" />
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input v-model="form.email" type="email" class="w-full rounded border border-gray-300 p-2" />
                </div>

                <div>
                    <label class="block font-semibold mb-1">Salutation</label>
                    <select v-model="form.salutation_id" class="w-full rounded border border-gray-300 p-2">
                        <option v-for="salutation in salutations" :key="salutation.id" :value="salutation.id">
                            {{ salutation.salutation_value }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Statut</label>
                    <select v-model="form.status_user_id" class="w-full rounded border border-gray-300 p-2">
                        <option v-for="status in statuses" :key="status.id" :value="status.id">
                            {{ status.status_value }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Rôle</label>
                    <select v-model="form.role" class="w-full rounded border border-gray-300 p-2">
                        <option value="user">Utilisateur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>

                <div class="text-right pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
