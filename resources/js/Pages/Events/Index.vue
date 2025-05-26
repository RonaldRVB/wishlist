<script setup>
import ModalDelete from "@/Components/Modals/ModalDelete.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    events: Array,
});

document.title = "Wishlist - Événements";


const showDeleteModal = ref(false);
const eventToDelete = ref(null);

function confirmDelete(event) {
    eventToDelete.value = event;
    showDeleteModal.value = true;
}

function deleteEvent() {
    if (eventToDelete.value) {
        router.delete(
            route("events.destroy", { event: eventToDelete.value.id }),
        );
        showDeleteModal.value = false;
    }
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

    <Head title="Wishlist - Événements" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-3 px-6 flex flex-col items-center">
        <div class="not-prose pt-24 max-w-7xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Liste des événements
                </h1>

                <button @click="router.visit(route('events.create'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    + Nouvel événement
                </button>
            </div>

            <div class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full">
                <div class="overflow-x-auto rounded-xl">
                    <table class="w-full">
                        <thead class="bg-teal-200 text-black">
                            <tr>
                                <th class="text-left px-4 py-2">Titre</th>
                                <th class="text-left px-4 py-2">Date</th>
                                <th class="text-left px-4 py-2">Image</th>
                                <!-- <th class="text-left px-4 py-2">Slug</th> -->
                                <th class="text-left px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="event in events" :key="event.id" class="border-t border-green-500">
                                <td class="px-4 py-2 font-bold">
                                    {{ event.title }}
                                </td>
                                <td class="px-4 py-2 font-bold">
                                    {{
                                        new Date(
                                            event.event_date,
                                        ).toLocaleDateString("fr-FR")
                                    }}
                                </td>

                                <td class="px-4 py-2">
                                    <div class="group relative w-16 h-16 overflow-hidden rounded-xl shadow">
                                        <img :src="'/storage/' + (event.custom_image || event.default_image?.path) + '?v=' + Date.now()"
                                            :alt="event.title"
                                            class="h-16 w-16 object-cover rounded-lg shadow cursor-pointer"
                                            @click="openModal(event.custom_image || event.default_image?.path)" />
                                    </div>
                                </td>

                                <!-- <td class="px-4 py-2 font-bold italic text-gray-600">
                                    {{ event.slug }}
                                </td> -->
                                <td class="px-4 py-2 h-16 pt-6 flex items-center justify-start space-x-2">
                                    <button @click="
                                        router.visit(
                                            route('events.show', event.id),
                                        )
                                        " class="text-blue-700 hover:text-teal-900" title="Voir">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 5c-7.633 0-10 7-10 7s2.367 7 10 7 10-7 10-7-2.367-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                                        </svg>
                                    </button>

                                    <button @click="
                                        router.visit(
                                            route('events.edit', event.id),
                                        )
                                        " class="text-blue-700 hover:text-[#F87171]" title="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM4 16a1 1 0 100 2h12a1 1 0 100-2H4z" />
                                        </svg>
                                    </button>

                                    <!-- Bouton Supprimer -->
                                    <button @click="confirmDelete(event)" title="Supprimer"
                                        class="text-blue-700 hover:text-[#F87171]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
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

                <!-- Message si aucun événement -->
                <div v-if="events.length === 0" class="p-6 text-center text-blue-800 font-semibold">
                    Tu n’as encore créé aucun événement.
                    <p class="text-sm text-gray-600 mt-2">
                        Cette page est réservée aux événements que
                        <strong>tu as créés</strong>.<br />
                        Si tu as été invité à un événement, tu peux les
                        consulter dans
                        <strong>
                            <a @click.prevent="
                                router.visit(route('invitations.mine'))
                                " href="#" class="text-blue-700 underline hover:text-blue-900 font-bold">
                                « Mes invitations »</a></strong>.
                    </p>
                </div>
            </div>
        </div>

        <!-- ModalDelete -->
        <ModalDelete :show="showDeleteModal" :entity="eventToDelete" routeName="events.destroy" labelKey="title"
            @close="showDeleteModal = false" @confirm="deleteEvent" />
        <!-- Modal image agrandie -->
        <div v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="relative bg-[#E3EFFD] p-4 rounded-lg shadow-xl flex justify-center items-center">
                <img :src="modalImageUrl" alt="Image de l'événement agrandie"
                    class="max-w-full max-h-[70vh] object-contain rounded" />
                <button @click="closeModal"
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2">
                    ✕
                </button>
            </div>
        </div>
    </div>
</template>
