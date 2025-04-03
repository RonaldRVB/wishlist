<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'


defineOptions({ layout: AppLayout })

defineProps({
    images: Array,
})

const showDeleteModal = ref(false)
const imageToDelete = ref(null)

const confirmDelete = (image) => {
    imageToDelete.value = image
    showDeleteModal.value = true
}

const deleteImage = () => {
    if (imageToDelete.value) {
        router.delete(route('images.destroy', imageToDelete.value.id))
        showDeleteModal.value = false
    }
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <!-- Titre centré -->
        <div class="not-prose w-full max-w-7xl mb-6 text-center">
            <h1 class="text-3xl font-bold text-blue-900">Images par défaut</h1>
        </div>

        <!-- Bouton centré -->
        <div class="mb-6">
            <button @click="router.visit(route('images.create'))"
                class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                + Ajouter une image
            </button>
        </div>

        <!-- Tableau -->
        <div class="max-w-7xl w-full">
            <table class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full">
                <thead class="bg-teal-200 text-black">
                    <tr>
                        <th class="text-center px-4 py-2">Label</th>
                        <th class="text-center px-4 py-2">Aperçu</th>
                        <th class="text-center px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="image in images" :key="image.id" class="border-t border-green-500 text-center">
                        <td class="px-4 py-2 font-bold">{{ image.label }}</td>
                        <td class="px-4 py-2">
                            <img :src="image.path + '?v=' + Date.now()" alt="Image par défaut"
                                class="h-16 w-auto rounded shadow mx-auto" />
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <button @click="router.visit(route('images.show', image.id))"
                                class="text-indigo-700 hover:text-indigo-900" title="Voir">
                                <!-- 👁 -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-blue-700 hover:text-[#F87171]" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 5c-7.633 0-10 7-10 7s2.367 7 10 7 10-7 10-7-2.367-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                                </svg>

                            </button>

                            <button @click="router.visit(route('images.editReplace', { image: image.id }))"
                                title="Remplacer">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-blue-700 hover:text-[#F87171]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582M20 20v-5h-.581M5 19a9 9 0 0014.764-6M19 5A9 9 0 004.236 11" />
                                </svg>
                            </button>
                            <button @click="router.visit(route('images.destroy', { image: image.id }))"
                                title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-blue-700 hover:text-[#F87171]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h10" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Confirmer la suppression</h2>
                <p class="text-gray-600 mb-6">
                    Es-tu sûr de vouloir supprimer l’image "<strong>{{ imageToDelete?.label }}</strong>" ?
                </p>
                <div class="flex justify-end space-x-4">
                    <button @click="showDeleteModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                        Annuler
                    </button>
                    <button @click="deleteImage" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
