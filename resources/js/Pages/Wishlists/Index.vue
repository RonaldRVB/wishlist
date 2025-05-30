<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";
import ModalDelete from "@/Components/Modals/ModalDelete.vue";
import ModalInfo from "@/Components/Modals/ModalInfo.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout });

document.title = "Wishlist - Liste de souhaits";

const page = usePage();
const flashError = ref("");

// Surveille les flashs à chaque rendu
watchEffect(() => {
    flashError.value = page.props.flash?.error || "";
});

// Props
const props = defineProps({
    wishlists: Array,
    flashError: String,
});

// Modal suppression
const showDeleteModal = ref(false);
const wishlistToDelete = ref(null);

// Modal info (liste perso non modifiable/supprimable)
const showInfoModal = ref(false);
const infoModalContent = ref({ title: "", message: "" });

function openDeleteModal(wishlist) {
    if (wishlist.title === "Ma liste personnelle") {
        infoModalContent.value = {
            title: "Suppression impossible",
            message: "La liste personnelle ne peut pas être supprimée.",
        };
        showInfoModal.value = true;
    } else {
        wishlistToDelete.value = wishlist;
        showDeleteModal.value = true;
    }
}

function openErrorModalFromServer(message) {
    infoModalContent.value = {
        title: "Création impossible",
        message,
    };
    showInfoModal.value = true;
}

function confirmDelete() {
    router.delete(route("wishlists.destroy", wishlistToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            wishlistToDelete.value = null;
        },
    });
}

function editWishlist(wishlist) {
    if (wishlist.title === "Ma liste personnelle") {
        infoModalContent.value = {
            title: "Modification impossible",
            message: "La liste personnelle ne peut pas être modifiée.",
        };
        showInfoModal.value = true;
    } else {
        router.visit(route("wishlists.edit", wishlist.id));
    }
}

function createNewWishlist() {
    router.visit(route("wishlists.create"));
}

if (props.flashError) {
    openErrorModalFromServer(props.flashError);
}
</script>

<template>

    <Head title="Wishlist - Liste de souhaits" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="max-w-5xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">Mes wishlists</h1>

                <div class="flex gap-4">
                    <button @click="createNewWishlist"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        + Nouvelle liste
                    </button>
                    <button @click="router.visit(route('gifts.create'))"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        + Ajouter cadeau
                    </button>
                    <button @click="router.visit(route('events.create'))"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        + Nouvel événement
                    </button>
                </div>
            </div>

            <div v-if="flashError" class="mb-4 p-4 bg-red-200 text-red-800 rounded-lg shadow">
                {{ flashError }}
            </div>

            <div class="overflow-x-auto rounded-xl">
                <table class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full">
                    <thead class="bg-teal-200 text-black">
                        <tr>
                            <th class="w-1/4 text-left px-4 py-2">Nom</th>
                            <th class="w-1/2 text-left px-4 py-2">Description</th>
                            <th class="w-1/4 text-left px-4 py-2">
                                Événements liés
                            </th>
                            <th class="w-1/6 text-right px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <template v-for="wishlist in wishlists" :key="wishlist.id">
                        <tr v-if="wishlist.title === 'Ma liste personnelle'"
                            class="border-t border-green-500 bg-[#F2F8FF]">
                            <td class="px-4 py-2 font-bold text-blue-800 whitespace-normal">
                                {{ wishlist.title }}
                            </td>

                            <td colspan="2" class="px-4 py-2 text-gray-700 whitespace-normal">
                                {{ wishlist.description }}
                            </td>

                            <td class="px-4 py-2 text-right">
                                <div class="flex justify-end space-x-4">
                                    <!-- Voir -->
                                    <button @click="
                                        router.visit(
                                            route(
                                                'wishlists.show',
                                                wishlist.id,
                                            ),
                                        )
                                        " class="text-blue-700 hover:text-indigo-800" title="Voir">
                                        <!-- œil -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z" />
                                        </svg>
                                    </button>

                                    <!-- Modifier -->
                                    <button @click="editWishlist(wishlist)" class="text-blue-700 hover:text-red-400"
                                        title="Modifier">
                                        <!-- crayon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M3 17.25V21h3.75l11.065-11.065-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.83z" />
                                        </svg>
                                    </button>

                                    <!-- Supprimer -->
                                    <button @click="openDeleteModal(wishlist)"
                                        class="text-blue-700 hover:text-[#F87171]" title="Supprimer">
                                        <!-- poubelle -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-else class="border-t border-green-500 hover:bg-blue-50 transition">
                            <td class="px-4 py-2 font-bold text-blue-800 whitespace-normal">
                                {{ wishlist.title }}
                            </td>

                            <td class="px-4 py-2 text-gray-700 whitespace-normal">
                                {{ wishlist.description || "—" }}
                            </td>

                            <td class="px-4 py-2 text-gray-700 whitespace-normal">
                                <template v-if="wishlist.events?.length">
                                    <span v-for="event in wishlist.events" :key="event.id"
                                        class="block text-sm text-indigo-700">
                                        {{ event.title }}
                                    </span>
                                </template>
                                <template v-else> — </template>
                            </td>

                            <td class="px-4 py-2 text-right">
                                <div class="flex justify-end space-x-4">
                                    <!-- Voir -->
                                    <button @click="
                                        router.visit(
                                            route(
                                                'wishlists.show',
                                                wishlist.id,
                                            ),
                                        )
                                        " class="text-blue-700 hover:text-indigo-800" title="Voir">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z" />
                                        </svg>
                                    </button>

                                    <!-- Modifier -->
                                    <button @click="editWishlist(wishlist)" class="text-blue-700 hover:text-red-400"
                                        title="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M3 17.25V21h3.75l11.065-11.065-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.83z" />
                                        </svg>
                                    </button>

                                    <!-- Supprimer -->
                                    <button @click="openDeleteModal(wishlist)"
                                        class="text-blue-700 hover:text-[#F87171]" title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </table>
            </div>
        </div>

        <!-- Modals -->
        <ModalDelete v-if="showDeleteModal" :show="showDeleteModal" :entity="wishlistToDelete"
            routeName="wishlists.destroy" labelKey="title" @close="showDeleteModal = false" @confirm="confirmDelete" />

        <ModalInfo v-if="showInfoModal" :show="showInfoModal" :title="infoModalContent.title"
            :message="infoModalContent.message" @close="showInfoModal = false" />
    </div>
</template>
