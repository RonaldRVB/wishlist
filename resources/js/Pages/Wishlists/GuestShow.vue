<script setup>
import { usePage, router } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";

document.title = "Wishlist - Liste de souhaits";

defineProps({
    wishlist: Object,
    gifts: Array,
});

function reserveGift(giftId) {
    router.post(
        route("gifts.reserve", { gift: giftId }),
        {},
        {
            preserveScroll: true,
        },
    );
}

const page = usePage();
const user = page.props.auth.user;

const isGuest = user?.salutation_id === 5;

function logout() {
    router.post(route("logout"));
}
</script>

<template>

    <Head title="Wishlist - Liste de souhaits" />
    <div class="min-h-screen bg-[#D6E9FC] py-10 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <h1 class="text-3xl font-bold text-blue-900 text-center sm:text-left">
                    Liste de souhaits : {{ wishlist.name }}
                </h1>

                <div v-if="isGuest" class="mt-4 sm:mt-0 flex justify-center sm:justify-end">
                    <button @click="logout"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-xl">
                        🔓 Se déconnecter
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div v-for="gift in gifts" :key="gift.id"
                    class="bg-white p-4 rounded-xl shadow border border-blue-300 flex flex-col items-center">
                    <img v-if="gift.image" :src="'/storage/' + gift.image" alt="Image du cadeau"
                        class="w-full h-40 object-cover rounded-lg mb-4" />
                    <h2 class="text-lg font-bold text-blue-900 text-center mb-2">
                        {{ gift.name }}
                    </h2>
                    <p class="text-sm text-gray-700 text-center mb-4">
                        {{ gift.description }}
                    </p>
                    <a v-if="gift.purchase_url" :href="gift.purchase_url"
                        class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-full text-sm mb-2"
                        target="_blank">
                        Voir le produit
                    </a>


                    <button v-if="!gift.is_reserved" @click="reserveGift(gift.id)"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl font-bold">
                        🎯 Réserver ce cadeau
                    </button>

                    <p v-else class="text-red-600 font-semibold mt-2">
                        🔒 Déjà réservé
                    </p>
                </div>
            </div>
            <!-- Espace visuel sous le bloc invité -->
            <div class="h-16"></div>
            <!-- ✅ MESSAGE INVITÉ avec espacement -->
            <div v-if="isGuest" class="mt-10">
                <div
                    class="bg-red-100 text-red-800 px-6 py-3 rounded-xl border border-red-300 flex items-center justify-center space-x-2 font-semibold">
                    <span>⚠️</span>
                    <span>Accès invité temporaire</span>
                </div>
            </div>
        </div>
    </div>
</template>
