<script setup>
import { computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    event: Object,
    defaultImages: Array,
})

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    event_date: props.event.event_date,
    default_image_id: props.event.default_image_id,
    custom_image: null, // nouveau fichier à uploader
    _method: 'put',
})

const imagePreview = computed(() => {
    return form.custom_image ? URL.createObjectURL(form.custom_image) : null
})

function handleImageUpload(event) {
    const file = event.target.files[0]

    if (file && file.size > 5 * 1024 * 1024) {
        alert('L’image est trop lourde. Elle ne peut pas dépasser 5MB.')
        event.target.value = '' // Réinitialise le champ
        form.custom_image = null
    } else {
        form.custom_image = file
    }
}

</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Modifier l’événement</h1>

                <button type="button" @click="router.visit(route('events.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    Retour
                </button>
            </div>

            <form @submit.prevent="form.post(route('events.update', event.id), {
                preserveScroll: true,
                _method: 'put',
            })" enctype="multipart/form-data"
                class="space-y-4 bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6">


                <!-- Titre -->
                <div>
                    <label class="font-semibold text-blue-900">Titre de l’événement</label>
                    <input type="text" v-model="form.title" class="w-full rounded-lg border-gray-300 mt-1 shadow-sm" />
                </div>

                <!-- Description -->
                <div>
                    <label class="font-semibold text-blue-900">Description</label>
                    <textarea v-model="form.description"
                        class="w-full rounded-lg border-gray-300 mt-1 shadow-sm"></textarea>
                </div>

                <!-- Date -->
                <div>
                    <label class="font-semibold text-blue-900">Date de l’événement</label>
                    <input type="date" v-model="form.event_date"
                        class="w-full rounded-lg border-gray-300 mt-1 shadow-sm" />
                </div>

                <!-- Image par défaut -->
                <div>
                    <label class="font-semibold text-blue-900 block mb-2">Image par défaut</label>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-4">
                        <div v-for="img in defaultImages" :key="img.id"
                            :class="['rounded border cursor-pointer p-1 transition-all', form.default_image_id === img.id ? 'border-2 border-blue-600 ring-2 ring-blue-300' : 'border-gray-300']"
                            @click="form.default_image_id = img.id" style="width: 96px;">
                            <img :src="img.path" :alt="img.label" class="h-24 w-auto mx-auto rounded" />
                        </div>
                    </div>
                </div>

                <!-- Image personnalisée -->
                <div>
                    <label class="font-semibold text-blue-900 block mb-1">Image personnalisée (facultative)</label>
                    <input type="file" accept="image/*" class="w-full" @change="handleImageUpload" />
                </div>

                <div v-if="imagePreview" class="mt-4">
                    <p class="text-gray-700 italic mb-1">Aperçu :</p>
                    <img :src="imagePreview" alt="Aperçu image" class="max-h-40 rounded shadow border" />
                </div>

                <!-- Bouton -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
                        :disabled="form.processing">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
