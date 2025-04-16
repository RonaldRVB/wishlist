<script setup>
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const form = useForm({
    title: '',
    version: '',
    content: '',
    is_active: false,
})

const submit = () => {
    form.post(route('documents.store'))
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <!-- Titre + bouton retour alignés -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Créer un document</h1>
                <button type="button" @click="router.visit(route('documents.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    Retour
                </button>
            </div>
        </div>


        <div class="max-w-4xl w-full bg-[#E3EFFD] p-6 rounded-xl shadow border border-blue-300">
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-1">Titre</label>
                    <input id="title" v-model="form.title" type="text"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="version" class="block text-sm font-bold text-gray-700 mb-1">Version</label>
                    <input id="version" v-model="form.version" type="text"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="content" class="block text-sm font-bold text-gray-700 mb-1">Contenu</label>
                    <textarea id="content" v-model="form.content" rows="10"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-y"></textarea>
                </div>

                <div class="flex items-center space-x-2">
                    <input id="is_active" v-model="form.is_active" type="checkbox"
                        class="rounded text-blue-600 border-gray-300 shadow-sm focus:ring-blue-500" />
                    <label for="is_active" class="text-sm text-gray-700">Actif ?</label>
                </div>

                <div class="text-right space-x-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
                        Créer le document
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
