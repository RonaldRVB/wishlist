<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    gifts: Array,
});

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
            <h1 class="text-3xl font-bold text-blue-900 mb-6">Mes cadeaux</h1>

            <div
                class="bg-[#E3EFFD] shadow rounded-xl overflow-hidden border border-blue-300"
            >
                <table class="w-full">
                    <thead class="bg-teal-200 text-black">
                        <tr>
                            <th class="text-left px-4 py-2">Nom</th>
                            <th class="text-left px-4 py-2">Description</th>
                            <th class="text-left px-4 py-2">Image</th>
                            <th class="text-left px-4 py-2">Wishlist</th>
                            <th class="text-left px-4 py-2">Événements liés</th>
                            <th class="text-left px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="gift in gifts"
                            :key="gift.id"
                            class="border-t border-green-500"
                        >
                            <td class="px-4 py-2 font-bold">{{ gift.name }}</td>
                            <td class="px-4 py-2">
                                {{ gift.description || "—" }}
                            </td>
                            <td class="px-4 py-2">
                                <div
                                    v-if="gift.image"
                                    class="flex justify-center"
                                >
                                    <img
                                        :src="'/storage/' + gift.image"
                                        alt="Image du cadeau"
                                        class="w-16 h-16 object-cover rounded-xl cursor-pointer"
                                        @click="openModal(gift.image)"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="text-gray-400 italic text-sm"
                                >
                                    Pas d'image
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <ul class="space-y-1">
                                    <li
                                        v-for="wishlist in gift.wishlists"
                                        :key="wishlist.id"
                                    >
                                        <div
                                            class="text-blue-800 font-semibold"
                                        >
                                            {{ wishlist.title }}
                                        </div>
                                    </li>
                                </ul>
                            </td>
                            <td class="px-4 py-2">
                                <ul class="space-y-1">
                                    <li
                                        v-for="wishlist in gift.wishlists"
                                        :key="wishlist.id"
                                    >
                                        <div
                                            v-if="wishlist.events.length > 0"
                                            class="text-sm text-gray-700"
                                        >
                                            <span
                                                v-for="(
                                                    event, index
                                                ) in wishlist.events"
                                                :key="event.id"
                                            >
                                                {{ event.title
                                                }}<span
                                                    v-if="
                                                        index <
                                                        wishlist.events.length -
                                                            1
                                                    "
                                                    >,
                                                </span>
                                            </span>
                                        </div>
                                        <div
                                            v-else
                                            class="text-sm italic text-gray-500"
                                        >
                                            (non liée à un événement)
                                        </div>
                                    </li>
                                </ul>
                            </td>
                            <td
                                class="px-4 py-2 text-center align-middle space-x-4"
                            >
                                <button
                                    @click="
                                        router.visit(
                                            route('gifts.show', gift.id)
                                        )
                                    "
                                    class="text-blue-700 hover:text-indigo-900"
                                    title="Voir"
                                >
                                    <!-- Icône Voir -->
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5
            5-5 5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    @click="
                                        router.visit(
                                            route('gifts.edit', gift.id)
                                        )
                                    "
                                    class="text-blue-700 hover:text-indigo-900"
                                    title="Modifier"
                                >
                                    <!-- Icône Modifier -->
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
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

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
    </div>
</template>
