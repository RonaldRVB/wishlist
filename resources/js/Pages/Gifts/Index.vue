<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import ModalDelete from "@/Components/Modals/ModalDelete.vue";
import { ref } from "vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    gifts: Array,
});

// Suppression
const showDeleteModal = ref(false);
const giftToDelete = ref(null);

function openDeleteModal(gift) {
    giftToDelete.value = gift;
    showDeleteModal.value = true;
}

function confirmDelete() {
    router.delete(route("gifts.destroy", giftToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            giftToDelete.value = null;
        },
    });
}

// Création
function createNewGift() {
    router.visit(route("gifts.create"));
}

// Modal image
const showModal = ref(false);
const modalImageUrl = ref("");

function openModal(imagePath) {
    modalImageUrl.value = "/storage/" + imagePath;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    modalImageUrl.value = "";
}
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center"
    >
        <div class="max-w-7xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Mes cadeaux</h1>
                <button
                    @click="createNewGift"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                >
                    + Nouveau cadeau
                </button>
            </div>

            <div
                class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full"
            >
                <table class="w-full table-auto">
                    <thead class="bg-teal-200 text-black text-center">
                        <tr>
                            <th class="px-4 py-2 w-[20%]">Nom</th>
                            <th class="px-4 py-2 w-[30%]">Description</th>
                            <th class="px-4 py-2 w-[15%]">Image</th>
                            <th class="px-4 py-2 w-[20%]">Wishlist</th>
                            <th class="px-4 py-2 w-[15%]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr
                            v-for="gift in gifts"
                            :key="gift.id"
                            class="border-t border-green-500 align-middle"
                        >
                            <td class="px-4 py-2 font-bold">{{ gift.name }}</td>
                            <td class="px-4 py-2">{{ gift.description }}</td>
                            <td class="px-4 py-2">
                                <img
                                    v-if="gift.image"
                                    :src="'/storage/' + gift.image"
                                    alt="Image du cadeau"
                                    class="h-16 w-16 object-cover rounded-xl mx-auto cursor-pointer"
                                    @click="openModal(gift.image)"
                                />
                            </td>
                            <td class="px-4 py-2">
                                <div
                                    v-for="wishlist in gift.wishlists"
                                    :key="wishlist.id"
                                    class="text-blue-800 font-semibold"
                                >
                                    {{ wishlist.title }}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <!-- actions (voir/modifier/supprimer) -->
                                <!-- Voir -->
                                <button
                                    @click="
                                        router.visit(
                                            route('gifts.show', gift.id),
                                        )
                                    "
                                    class="text-blue-700 hover:text-indigo-800"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z"
                                        />
                                    </svg>
                                </button>

                                <!-- Modifier -->
                                <button
                                    @click="
                                        router.visit(
                                            route('gifts.edit', gift.id),
                                        )
                                    "
                                    class="text-blue-700 hover:text-red-400"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M3 17.25V21h3.75l11.065-11.065-3.75-3.75L3 17.25zM21.41
                            6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1.003 1.003 0 0 0-1.42
                            0l-1.83 1.83 3.75 3.75 1.84-1.83z"
                                        />
                                    </svg>
                                </button>

                                <!-- Supprimer -->
                                <button
                                    @click="openDeleteModal(gift)"
                                    class="text-blue-700 hover:text-red-600"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"
                                        />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal suppression -->
        <ModalDelete
            :show="showDeleteModal"
            :entity="giftToDelete"
            routeName="gifts.destroy"
            labelKey="name"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
        />

        <!-- Modal image agrandie -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center z-50"
        >
            <div
                class="relative bg-[#E3EFFD] p-4 rounded-lg shadow-xl flex justify-center items-center"
            >
                <img
                    :src="modalImageUrl"
                    alt="Image du cadeau agrandie"
                    class="max-w-full max-h-[70vh] object-contain rounded"
                />
                <button
                    @click="closeModal"
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2"
                >
                    ✕
                </button>
            </div>
        </div>
    </div>
</template>
