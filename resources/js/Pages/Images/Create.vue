<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const form = useForm({
    label: '',
    image: null,
})

const submit = () => {
    form.post(route('images.store'), {
        forceFormData: true,
    })
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose">
            <h1 class="text-3xl font-bold mb-6 text-blue-900">Ajouter une image par défaut</h1>
        </div>

        <div class="max-w-4xl w-full bg-[#E3EFFD] p-6 rounded-xl shadow border border-blue-300">
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="label" class="block text-sm font-bold text-gray-700 mb-1">Label</label>
                    <input id="label" v-model="form.label" type="text"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="image" class="block text-sm font-bold text-gray-700 mb-1">Image</label>
                    <input id="image" type="file" @change="form.image = $event.target.files[0]"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="router.visit(route('images.index'))"
                        class="bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded-xl hover:bg-gray-400">
                        Annuler
                    </button>

                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        Ajouter l’image
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
