<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    wishlist: Object,
    gifts: Array,
    userEvents: Array,
});

function toggleEvent(eventId, isChecked) {
    const routeName = isChecked
        ? "wishlists.events.attach"
        : "wishlists.events.detach";

    router.post(route(routeName, { wishlist: props.wishlist.id }), {
        event_id: eventId,
    });
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10">
        <div class="max-w-7xl mx-auto px-6">
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4"
            >
                <h1 class="text-3xl font-bold text-blue-900">
                    {{ wishlist.title }}
                </h1>

                <div class="flex gap-4">
                    <button
                        @click="router.visit(route('gifts.create'))"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                    >
                        + Ajouter un cadeau
                    </button>

                    <button
                        @click="router.visit(route('wishlists.index'))"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                    >
                        Retour à mes listes
                    </button>
                </div>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
            >
                <div
                    v-for="gift in gifts"
                    :key="gift.id"
                    class="bg-[#E3EFFD] border border-blue-300 p-4 rounded-2xl shadow-sm flex flex-col items-center"
                >
                    <img
                        v-if="gift.image"
                        :src="'/storage/' + gift.image"
                        :alt="gift.name"
                        class="w-32 h-32 object-cover rounded-xl mb-3"
                    />

                    <h2
                        class="text-lg font-bold text-gray-900 mb-1 text-center"
                    >
                        {{ gift.name }}
                    </h2>

                    <p class="text-gray-700 text-sm mb-3 text-center">
                        {{ gift.description || "—" }}
                    </p>

                    <a
                        v-if="gift.purchase_url"
                        :href="gift.purchase_url"
                        target="_blank"
                        class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-full text-sm mb-2"
                    >
                        Voir le produit
                    </a>

                    <div
                        v-if="gift.is_reserved"
                        class="text-xs text-white bg-green-500 px-3 py-1 rounded-full shadow-sm"
                    >
                        Réservé
                    </div>
                </div>
            </div>
            <!-- Événements liés -->
            <div
                class="mt-12 bg-[#E3EFFD] border border-blue-300 p-6 rounded-xl shadow-md"
            >
                <h2 class="text-xl font-bold text-blue-900 mb-4">
                    Associer à des événements
                </h2>

                <div class="space-y-2">
                    <div
                        v-for="event in userEvents"
                        :key="event.id"
                        class="flex items-center space-x-2"
                    >
                        <input
                            type="checkbox"
                            :id="'event-' + event.id"
                            :checked="
                                wishlist.events.some((e) => e.id === event.id)
                            "
                            @change="
                                toggleEvent(event.id, $event.target.checked)
                            "
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                        />

                        <label :for="'event-' + event.id" class="text-gray-800">
                            {{ event.title }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
