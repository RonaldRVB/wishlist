<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout })

document.title = "Wishlist - Invitations";

const props = defineProps({
    eventId: Number,
})

const form = useForm({
    event_id: props.eventId,
    emails: [''],
})

function addEmailField() {
    form.emails.push('')
}

function removeEmailField(index) {
    if (index !== 0) {
        form.emails.splice(index, 1)
    }
}

function submit() {
    form.post(route('invitations.storeMultiple'), {
        preserveScroll: true,
    })
}
</script>

<template>

    <Head title="Wishlist - Invitations" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-3xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Inviter des participants</h1>

                <div class="flex space-x-2">
                    <button @click="router.visit(route('events.show', eventId))"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        Retour à l’événement
                    </button>

                    <button @click="router.visit(route('events.index'))"
                        class="bg-indigo-400 text-blue-900 font-semibold px-4 py-2 rounded-xl hover:bg-indigo-500">
                        Tous les événements
                    </button>

                </div>
            </div>

            <div class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div v-for="(email, index) in form.emails" :key="index" class="flex items-center space-x-2">
                        <input type="email" v-model="form.emails[index]"
                            class="w-full rounded border border-gray-300 p-2 shadow-sm"
                            placeholder="exemple@mail.com" />
                        <button v-if="index !== 0" type="button" @click="removeEmailField(index)"
                            class="text-red-600 hover:text-red-800 font-bold text-lg">
                            &times;
                        </button>
                    </div>

                    <div v-if="form.errors.emails" class="text-red-600 text-sm mt-1">
                        {{ form.errors.emails }}
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="addEmailField"
                            class="bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600">
                            + Ajouter un champ
                        </button>

                        <button type="submit"
                            class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
                            :disabled="form.processing">
                            Envoyer les invitations
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
