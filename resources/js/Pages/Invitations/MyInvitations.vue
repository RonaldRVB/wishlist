<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout });

document.title = "Wishlist - Invitations";

const props = defineProps({
    participations: Array, // nouveau nom
});
</script>

<template>

    <Head title="Wishlist - Invitations" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Mes invitations
                </h1>
            </div>

            <ul class="space-y-4">
                <li v-for="participation in participations" :key="participation.id"
                    class="bg-[#E3EFFD] border border-blue-300 p-4 rounded-xl shadow flex justify-between items-center">
                    <div>
                        <p class="text-blue-800 font-semibold">
                            Événement : {{ participation.event.title }}
                        </p>
                        <p class="text-gray-600 text-sm">
                            Date : {{ participation.event.event_date }}
                        </p>
                    </div>

                    <button @click="
                        router.visit(
                            route('wishlists.byEvent', {
                                event: participation.event.id,
                            }),
                        )
                        " class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        Voir les wishlists
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>
