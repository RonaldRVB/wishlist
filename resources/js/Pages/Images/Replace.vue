<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    image: Object
})

const form = useForm({
    file: null,
})

const imagePreview = computed(() => {
    return form.file ? URL.createObjectURL(form.file) : null
})


function handleFileChange(event) {
    form.file = event.target.files[0]

    if (form.errors.file) {
        delete form.errors.file
    }
}

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
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-900">Remplacer l’image : {{ image.label }}</h1>
                <button type="button" @click="router.visit(route('images.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    Retour
                </button>
            </div>

            <!-- Aperçu actuel -->
            <div>
                <p class="text-sm text-gray-700 mb-2">Aperçu actuel :</p>
                <img :src="previewPath" :alt="image.label" class="max-w-xl rounded-xl border shadow" />
            </div>

            <!-- Formulaire de remplacement -->
            <form @submit.prevent="submit" class="space-y-4">
                <label for="file" class="block text-sm font-bold text-gray-700 mb-1">Nouvelle image (max. 5 Mo)</label>

                <input type="file" @change="handleFileChange" class="block w-full" accept="image/*" required />

                <!-- Aperçu de la nouvelle image -->
                <div v-if="imagePreview" class="mt-4">
                    <p class="text-gray-700 italic mb-1">Aperçu de la nouvelle image :</p>
                    <img :src="imagePreview" alt="Nouvelle image" class="max-h-40 rounded shadow border" />
                </div>

                <!-- Message d'erreur -->
                <div v-if="form.errors.file" class="text-red-600 text-sm mt-2">
                    {{ form.errors.file }}
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                        :disabled="form.processing">
                        Remplacer l’image
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>
