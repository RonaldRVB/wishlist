<script setup>
import { marked } from 'marked'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps({
    document: Object,
})
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <div class="not-prose max-w-4xl w-full">
                <!-- Ligne titre + modifier + retour -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-blue-900">Document : {{ document.label }}</h1>

                    <div class="flex items-center space-x-4">
                        <!-- Bouton Modifier -->
                        <button @click="router.visit(route('documents.edit', document.id))"
                            class="bg-teal-500 hover:bg-teal-600 text-white font-semibold px-4 py-2 rounded-xl">
                            Modifier
                        </button>

                        <!-- Bouton Retour -->
                        <button type="button" @click="router.visit(route('documents.index'))"
                            class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                            Retour
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl w-full">
            <div class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full p-6 border border-blue-300">
                <p class="text-gray-800 text-justify leading-relaxed mb-6">
                    <span class="font-bold text-lg">Version :</span>
                    <span class="font-bold text-lg ml-2" v-html="marked.parseInline(`**${document.version}**`)"></span>
                </p>


                <div class="prose max-w-none text-justify text-gray-800 leading-relaxed"
                    v-html="marked(document.content)" />

            </div>
        </div>
    </div>
</template>
