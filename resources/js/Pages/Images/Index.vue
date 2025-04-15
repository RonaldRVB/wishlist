<script setup>
import ModalDelete from '@/Components/Modals/ModalDelete.vue'
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
                                    class="h-5 w-5 text-blue-700 hover:text-[#F87171]" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M4.293 6.707a8 8 0 0111.414 0L13 9h5V4l-2.293 2.293a10 10 0 00-14.142 0l1.414 1.414zM15.707 13.293a8 8 0 01-11.414 0L7 11H2v5l2.293-2.293a10 10 0 0014.142 0l-1.414-1.414z" />
                                </svg>
                            </button>

                            <button @click="confirmDelete(image)" title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-blue-700 hover:text-[#F87171]" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal sombre et flouté -->
        <ModalDelete :show="showDeleteModal" :entity="imageToDelete" routeName="images.destroy" labelKey="label"
            @close="showDeleteModal = false" @confirm="deleteImage" />



    </div>
</template>
