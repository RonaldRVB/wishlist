<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
defineOptions({ layout: AppLayout })

import { useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    image: Object
})

const form = useForm({
    file: null,
})

const previewPath = computed(() => props.image?.path)

const submit = () => {
    form.post(route('images.replace', { image: props.image.id }), {
        forceFormData: true,
    })
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6">
        <div class="max-w-2xl mx-auto bg-[#E3EFFD] p-6 rounded-xl shadow space-y-10">
            <h1 class="text-2xl font-bold">Remplacer l’image : {{ image.label }}</h1>

            <!-- Aperçu actuel -->
            <div>
                <p class="text-sm text-gray-700 mb-2">Aperçu actuel :</p>
                <img :src="previewPath" :alt="image.label" class="max-w-xl rounded-xl border shadow" />
            </div>

            <!-- Formulaire de remplacement -->
            <form @submit.prevent="submit" class="space-y-4">
                <input type="file" @change="form.file = $event.target.files[0]" class="block w-full" accept="image/*"
                    required />

                <div class="flex justify-between items-center">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                        :disabled="form.processing">
                        Remplacer l’image
                    </button>

                    <button type="button" @click="router.visit(route('images.index'))"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        Retour
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>
