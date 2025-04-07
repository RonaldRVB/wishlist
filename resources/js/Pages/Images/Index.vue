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

function confirmDelete(image) {
    imageToDelete.value = image
    showDeleteModal.value = true
}

function deleteImage() {
    if (imageToDelete.value) {
        router.delete(route('images.destroy', { defaultImage: imageToDelete.value.id }))
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
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Voir -->
                                <button @click="router.visit(route('images.show', image.id))"
                                    class="text-indigo-700 hover:text-indigo-900" title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-blue-700 hover:text-[#F87171]" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 5c-7.633 0-10 7-10 7s2.367 7 10 7 10-7 10-7-2.367-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                                    </svg>
                                </button>

                                <!-- Remplacer -->
                                <button @click="router.visit(route('images.editReplace', { image: image.id }))"
                                    title="Remplacer" class="text-blue-700 hover:text-[#F87171]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 12.5l2.5 3.01L14.5 13l4.5 6H5l3.5-4.5zM8 8a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
                                    </svg>
                                </button>

                                <!-- Modifier le titre -->
                                <button @click="router.visit(route('images.edit', image.id))"
                                    class="text-blue-700 hover:text-[#F87171]" title="Modifier le titre">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM4 16a1 1 0 100 2h12a1 1 0 100-2H4z" />
                                    </svg>
                                </button>

                                <!-- Bouton Supprimer -->
                                <button @click="confirmDelete(image)" title="Supprimer"
                                    class="text-blue-700 hover:text-[#F87171]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                    </svg>
                                </button>
                            </div>
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

        <!-- Modal sombre de suppression pour image -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70">
            <div class="bg-[#D6E9FC] text-blue-900 p-6 rounded-xl shadow-xl max-w-md w-full">
                <h2 class="text-lg p-3 bg-indigo-200 rounded-xl font-bold mb-4">Confirmer la suppression</h2>
                <p class="text-blue-900 font-bold mb-6">
                    Es-tu sûr de vouloir supprimer l’image :<br><br>
                    <strong class="text-2xl font-bold">{{ imageToDelete?.label }} ?</strong><br><br>
                    Cette action est <span class="text-lg italic text-red-400 font-bold">irréversible</span>.
                </p>

                <div class="flex justify-end space-x-4">
                    <button @click="showDeleteModal = false"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Annuler
                    </button>
                    <button @click="deleteImage" class="px-4 py-2 bg-red-400 text-white rounded hover:bg-red-700">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>


    </div>
</template>
