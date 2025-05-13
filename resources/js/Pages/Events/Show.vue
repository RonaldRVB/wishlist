<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ModalDelete from "@/Components/Modals/ModalDelete.vue";
import { ref } from "vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: Object,
    invitations: Array,
});

const baseUrl = window.location.origin;

const showDeleteModal = ref(false);
const invitationToDelete = ref(null);

function confirmDelete(invitation) {
    invitationToDelete.value = invitation;
    showDeleteModal.value = true;
}

function deleteInvitation() {
    if (invitationToDelete.value) {
        router.delete(
            route("invitations.destroy", {
                invitation: invitationToDelete.value.id,
            }),
        );
        showDeleteModal.value = false;
    }
}
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center"
    >
        <div class="not-prose max-w-7xl w-full">
            <!-- TITRE + BOUTONS -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Événement : {{ event.title }}
                </h1>
                <div class="flex space-x-4">
                    <button
                        @click="router.visit(route('events.index'))"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                    >
                        Retour
                    </button>
                    <button
                        @click="
                            router.visit(route('invitations.edit', event.id))
                        "
                        class="bg-teal-400 text-teal-900 font-semibold px-4 py-2 rounded-xl hover:bg-teal-500"
                    >
                        Ajouter des invités
                    </button>
                </div>
            </div>

            <!-- INFOS EVENEMENT -->
            <div
                class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 flex flex-col items-start mb-10"
            >
                <img
                    :src="
                        '/storage/' +
                        (event.custom_image || event.default_image?.path) +
                        '?v=' +
                        Date.now()
                    "
                    :alt="event.title"
                    class="max-w-full mx-auto max-h-[250px] rounded shadow mb-6"
                />

                <p class="mb-2">
                    <span class="font-semibold text-blue-900 pl-5"
                        >Description :</span
                    >
                    {{ event.description || "Aucune description fournie." }}
                </p>

                <p class="text-gray-700 mb-1">
                    <span class="font-semibold text-blue-900 pl-5">Date :</span>
                    {{ new Date(event.event_date).toLocaleDateString("fr-FR") }}
                </p>

                <p v-if="event.end_date" class="text-gray-700 mb-1 pl-5">
                    <span class="font-semibold text-blue-900"
                        >Fin prévue :</span
                    >
                    {{ new Date(event.end_date).toLocaleDateString("fr-FR") }}
                </p>

                <p class="mb-2">
                    <span class="font-semibold text-blue-900 pl-5"
                        >Visibilité :</span
                    >
                    {{ event.is_public ? "Public" : "Privé" }}
                </p>
            </div>

            <!-- INVITATIONS -->
            <div
                class="bg-[#E3EFFD] shadow rounded-xl overflow-hidden border border-blue-300 w-full"
            >
                <table class="w-full">
                    <thead class="bg-teal-200 text-black">
                        <tr>
                            <th class="text-left px-4 py-2">Email</th>
                            <th class="text-left px-4 py-2">Participant</th>
                            <th class="text-left px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="invitation in invitations"
                            :key="invitation.id"
                            class="border-t border-green-500"
                        >
                            <td class="px-4 py-2 font-bold">
                                {{ invitation.email }}
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    v-if="invitation.status_invitation_id === 2"
                                    class="text-green-700 font-bold"
                                >
                                    ✅ Acceptée
                                </span>
                                <span
                                    v-else-if="
                                        invitation.status_invitation_id === 3
                                    "
                                    class="text-red-700 font-bold"
                                >
                                    ❌ Refusée
                                </span>
                                <span v-else class="italic text-gray-600">
                                    En attente
                                </span>
                            </td>
                            <td class="px-4 py-1">
                                <button
                                    @click="confirmDelete(invitation)"
                                    title="Supprimer"
                                    class="text-blue-700 hover:text-[#F87171]"
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

            <!-- Modal suppression -->
            <ModalDelete
                v-if="showDeleteModal"
                :show="showDeleteModal"
                :entity="invitationToDelete"
                routeName="invitations.destroy"
                labelKey="email"
                @close="showDeleteModal = false"
                @confirm="deleteInvitation"
            />
        </div>
    </div>
</template>
