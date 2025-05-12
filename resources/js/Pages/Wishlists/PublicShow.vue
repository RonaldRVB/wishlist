<script setup>
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout });

const props = defineProps({
    wishlist: Object,
    eventId: Number,
});

// console.log(props.wishlist);

const page = usePage();
const user = page.props.auth.user;

const reserveGift = (giftId) => {
    router.post(
        route("gifts.reserve", { gift: giftId }),
        {},
        {
            onSuccess: () => {
                router.reload();
                console.log("Réservation réussie !");
            },
            onError: () => {
                console.warn("Erreur lors de la réservation.");
            },
        },
    );
};

const cancelReservation = (giftId) => {
    router.post(
        route("gifts.cancelReservation", { gift: giftId }),
        {},
        {
            onSuccess: () => {
                router.reload();
                console.log("Réservation annulée");
            },
            onError: () => {
                console.warn("Échec de l’annulation");
            },
        },
    );
};
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Liste publique – {{ wishlist.title }}
                </h1>
                <!-- <p class="text-sm text-gray-500">
                    ID de l'événement : {{ eventId }}
                </p> -->
                <button
                    v-if="eventId"
                    @click="
                        router.visit(
                            route('wishlists.byEvent', { event: eventId }),
                        )
                    "
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                >
                    Retour aux wishlists
                </button>
            </div>

            <p class="text-gray-800 mb-6 text-center">
                {{ wishlist.description }}
            </p>

            <div
                v-if="wishlist.gifts.length === 0"
                class="text-gray-600 italic text-center"
            >
                Aucun cadeau n’est encore présent dans cette wishlist.
            </div>

            <div
                v-else
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
            >
                <div
                    v-for="gift in wishlist.gifts"
                    :key="gift.id"
                    class="bg-[#E3EFFD] border border-blue-300 p-4 rounded-2xl shadow-sm flex flex-col items-center"
                >
                    {{ console.log(gift.name, gift.is_reserved) }}
                    <img
                        v-if="gift.image"
                        :src="`/storage/${gift.image}`"
                        :alt="gift.name"
                        class="w-32 h-32 object-cover rounded-xl mb-3"
                    />

                    <h2
                        class="text-lg font-bold text-gray-900 mb-1 text-center"
                    >
                        {{ gift.name }}
                    </h2>

                    <p class="text-gray-700 text-sm mb-3 text-center">
                        {{ gift.description }}
                    </p>

                    <a
                        v-if="gift.purchase_url"
                        :href="gift.purchase_url"
                        class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-full text-sm mb-2"
                        target="_blank"
                    >
                        Voir le produit
                    </a>

                    <!-- Badge si déjà réservé par quelqu’un d’autre -->
                    <div
                        v-if="gift.is_reserved && gift.reserved_by !== user?.id"
                        class="text-xs text-white bg-red-500 px-3 py-1 rounded-full shadow-sm"
                    >
                        Réservé
                    </div>

                    <!-- Si réservé par moi → bouton pour annuler -->
                    <button
                        v-else-if="
                            gift.is_reserved &&
                            user &&
                            gift.reserved_by === user.id
                        "
                        @click="cancelReservation(gift.id)"
                        class="text-xs text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full shadow-sm"
                    >
                        Annuler réservation
                    </button>

                    <!-- Si non réservé → bouton pour réserver -->
                    <button
                        v-else-if="user && user.id !== wishlist.user_id"
                        @click="reserveGift(gift.id)"
                        class="text-xs text-white bg-teal-600 hover:bg-teal-700 px-3 py-1 rounded-full shadow-sm"
                    >
                        Réserver ce cadeau
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
