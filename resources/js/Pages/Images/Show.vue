<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps({
    image: Object,
})
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <h1 class="text-3xl font-bold text-blue-900 text-center mb-6">Image : {{ image.label }}</h1>

            <div class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 flex flex-col items-center">
                <img :src="image.path" :alt="image.label" class="max-w-full max-h-[600px] rounded shadow mb-4" />
                <p class="text-gray-700 italic">Aperçu en taille réelle</p>
            </div>
        </div>
    </div>
</template>
