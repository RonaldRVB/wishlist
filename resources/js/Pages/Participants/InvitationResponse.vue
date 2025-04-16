<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    invitation: Object,
    status: String,
})

function registerAsUser() {
    router.visit(route('register'))
}

function continueAsGuest() {
    router.visit(route('participants.guest.access', props.invitation.token));
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] px-6 flex items-center justify-center">
        <div class="not-prose max-w-xl w-full bg-[#E3EFFD] rounded-xl shadow p-6 border border-blue-300 text-center">

            <h1 class="text-2xl font-bold text-blue-900 mb-4">
                {{ status === 'accepted' ? '🎉 Merci pour ta réponse !' : '😕 Invitation refusée' }}
            </h1>

            <p class="mb-6 text-blue-800">
                Tu as {{ status === 'accepted' ? 'accepté' : 'refusé' }} l’invitation à
                <strong>{{ invitation.event.title }}</strong>
            </p>

            <p class="mb-8 font-semibold text-blue-900">
                {{
                    status === 'accepted'
                        ? 'Souhaites-tu créer un compte pour suivre les cadeaux ou organiser tes propres événements?'
                        : 'Souhaites-tu créer un compte pour organiser tes propres événements ?'
                }}
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button @click="registerAsUser"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl font-bold">
                    ✅ Oui, je veux m’inscrire
                </button>

                <button v-if="status === 'accepted'" @click="continueAsGuest"
                    class="bg-indigo-400 hover:bg-indigo-500 text-blue-900 px-4 py-2 rounded-xl font-bold">
                    👀 Continuer sans compte
                </button>
            </div>
        </div>
    </div>
</template>
