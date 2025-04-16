<script setup>
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    invitation: Object,
})

const form = useForm({
    name: '',
    token: props.invitation.token,
})

function submit() {
    form.post(route('participants.storeGuest'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex items-center justify-center">
        <div class="not-prose max-w-md w-full bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6">
            <h1 class="text-2xl font-bold text-blue-900 text-center mb-6">
                👋 Accès invité
            </h1>

            <p class="text-blue-800 text-center mb-4">
                Pour consulter la wishlist, indique ton prénom ou un pseudo :
            </p>

            <form @submit.prevent="submit" class="space-y-4">
                <input type="text" v-model="form.name" class="w-full rounded border border-gray-300 p-2 shadow-sm"
                    placeholder="Ton prénom ou pseudo" />

                <div v-if="form.errors.name" class="text-red-600 text-sm">
                    {{ form.errors.name }}
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl">
                    Continuer
                </button>
            </form>
        </div>
    </div>
</template>
