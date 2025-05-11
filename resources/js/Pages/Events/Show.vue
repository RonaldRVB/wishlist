<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
defineOptions({ layout: AppLayout });

const props = defineProps({
    event: Object,
});

const baseUrl = window.location.origin;
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center"
    >
        <div class="not-prose max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Événement : {{ event.title }}
                </h1>

                <button
                    @click="router.visit(route('events.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                >
                    Retour
                </button>
            </div>

            <div
                class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 flex flex-col items-start"
            >
                <!-- Image en grand au-dessus -->
                <img
                    :src="
                        '/storage/' +
                        (event.custom_image || event.default_image?.path) +
                        '?v=' +
                        Date.now()
                    "
                    :alt="event.title"
                    class="max-w-full mx-auto max-h-[500px] rounded shadow mb-6"
                />

                <!-- Infos textuelles en dessous -->
                <p class="mb-2">
                    <span class="font-semibold text-blue-900 pl-5"
                        >Description :</span
                    >
                    {{ event.description || "Aucune description fournie." }}
                </p>

                <!-- Date -->
                <p class="text-gray-700 mb-1">
                    <span class="font-semibold text-blue-900 pl-5">Date :</span>
                    {{ new Date(event.event_date).toLocaleDateString("fr-FR") }}
                </p>

                <!-- Date de fin -->
                <p v-if="event.end_date" class="text-gray-700 mb-1 pl-5">
                    <span class="font-semibold text-blue-900"
                        >Fin prévue :</span
                    >
                    {{ new Date(event.end_date).toLocaleDateString("fr-FR") }}
                </p>

                <p class="mb-2 pl-5">
                    <span class="font-semibold text-blue-900">URL :</span>
                    {{ baseUrl + "/events/" + event.slug }}
                </p>

                <p class="mb-2">
                    <span class="font-semibold text-blue-900 pl-5"
                        >Visibilité :</span
                    >
                    {{ event.is_public ? "Public" : "Privé" }}
                </p>

                <!-- Boutons liés aux invitations -->
                <div class="flex flex-wrap mt-7 gap-4 justify-end mb-6">
                    <button
                        @click="
                            router.visit(
                                route('invitations.create', {
                                    event: event.id,
                                }),
                            )
                        "
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                    >
                        Invitations
                    </button>

                    <button
                        @click="
                            router.visit(
                                route('invitations.index', { event: event.id }),
                            )
                        "
                        class="bg-teal-400 text-teal-900 font-semibold px-4 py-2 rounded-xl hover:bg-teal-500"
                    >
                        Liste des invités
                    </button>

                    <button
                        @click="
                            router.visit(
                                route('invitations.edit', { event: event.id }),
                            )
                        "
                        class="bg-indigo-200 text-indigo-800 font-semibold px-4 py-2 rounded-xl hover:bg-indigo-300"
                    >
                        Ajouter des invités
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
