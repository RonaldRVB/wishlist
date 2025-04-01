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
        <div class="not-prose">
            <h1 class="text-3xl font-bold mb-2 text-blue-900">Document : {{ document.title }}</h1>
        </div>

        <!-- Bouton Modifier -->
        <div class="text-right mt-4 mb-6">
            <button @click="router.visit(route('documents.edit', document.id))"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Modifier
            </button>
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
