<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout })

document.title = "Wishlist - Images";

defineProps({
    image: Object,
})
</script>

<template>

    <Head title="Wishlist - Images" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Image : {{ image.label }}</h1>

                <!-- <p class="text-sm text-red-600">Chemin image : {{ image.path }}</p> -->

                <button @click="router.visit(route('images.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    Retour
                </button>
            </div>
            <div class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 flex flex-col items-center">
                <img :src="`/storage/${image.path}`" :alt="image.label"
                    class="max-w-full max-h-[600px] rounded shadow mb-4" />
                <p class="text-gray-700 italic">Aperçu en taille réelle</p>
            </div>
        </div>
    </div>
</template>
