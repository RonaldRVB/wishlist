<script setup>
import { marked } from "marked";
import { Head, router } from "@inertiajs/vue3";

// Props
defineProps({
    document: Object,
});

// Ajout du renderer pour ouvrir les liens dans un nouvel onglet
const renderer = new marked.Renderer();

renderer.link = function (token) {
    const href = token.href;
    const text = token.text;
    const title = token.title;
    const isMailto = href.startsWith("mailto:");
    const target = isMailto ? "" : ' target="_blank" rel="noopener noreferrer"';
    const titleAttr = title ? ` title="${title}"` : "";
    return `<a href="${href}"${titleAttr}${target}>${text}</a>`;
};

marked.setOptions({
    renderer,
    mangle: false,
    headerIds: false,
});
</script>

<template>

    <Head title="Wishlist - Mentions légales " />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">{{ document.title }}</h1>

                <button @click="router.visit('/')"
                    class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-indigo-700 transition">
                    Retour à l’accueil
                </button>
            </div>

            <div class="bg-[#E3EFFD] border border-blue-300 p-6 rounded-2xl shadow-sm">
                <div class="prose prose-sm sm:prose lg:prose-lg text-justify mx-auto"
                    v-html="marked(document.content)" />
            </div>
        </div>
    </div>
</template>
