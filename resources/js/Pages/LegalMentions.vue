<script setup>
import { marked } from 'marked'

// Déclarer les props
defineProps({
    document: Object,
})

// Ajout du renderer pour ouvrir les liens dans un nouvel onglet
const renderer = new marked.Renderer()

renderer.link = function (token) {
    // token = { href, text, title, ... }
    const href = token.href
    const text = token.text
    const title = token.title

    if (!href || typeof href !== 'string') {
        return text
    }

    const isMailto = href.startsWith('mailto:')
    const target = isMailto ? '' : ' target="_blank" rel="noopener noreferrer"'
    const titleAttr = title ? ` title="${title}"` : ''
    return `<a href="${href}"${titleAttr}${target}>${text}</a>`
}



marked.setOptions({
    renderer,
    mangle: false,
    headerIds: false,
})
</script>


<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1">
            <div class="bg-[#E3EFFD] border border-blue-300 p-6 rounded-2xl shadow-sm">
                <h1 class="text-5xl font-bold mb-6 mt-10 font-fraktur text-center">
                    {{ document.title }}
                </h1>

                <div class="prose max-w-none m-16 text-justify" v-html="marked(document.content)" />

            </div>
        </div>
    </div>
</template>
