<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: Object,
    wishlists: Array,
    userWishlist: Object, // null ou objet
});

function goToWishlist(slug) {
    router.visit(route("wishlists.public", { slug }));
}

function createWishlistForEvent() {
    router.visit(route("wishlists.create") + `?event_id=${props.event.id}`);
}
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center"
    >
        <div class="not-prose max-w-4xl w-full space-y-6">
            <h1 class="text-3xl font-bold text-blue-900">
                Wishlists pour l'événement : {{ event.title }}
            </h1>

            <ul class="space-y-2">
                <li
                    v-for="wishlist in wishlists"
                    :key="wishlist.id"
                    :class="[
                        'p-4 rounded-xl shadow flex justify-between items-center',
                        wishlist.user.id === event.user_id
                            ? 'bg-teal-100 border border-teal-400'
                            : 'bg-white border border-blue-300',
                    ]"
                >
                    <div class="font-semibold text-blue-800">
                        Liste de : {{ wishlist.user.name }}
                    </div>
                    <button
                        @click="goToWishlist(wishlist.slug)"
                        class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded-xl"
                    >
                        Voir
                    </button>
                </li>
            </ul>

            <div v-if="userWishlist === null" class="text-center mt-8">
                <p class="text-blue-800 mb-4 font-semibold">
                    Tu n’as pas encore créé ta wishlist pour cet événement.
                </p>
                <button
                    @click="createWishlistForEvent"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-xl font-bold"
                >
                    + Créer ma wishlist
                </button>
            </div>

            <div v-else class="text-center mt-8">
                <p class="text-blue-800 mb-4">
                    Tu as déjà une wishlist liée à cet événement.
                </p>
                <button
                    @click="goToWishlist(userWishlist.slug)"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold"
                >
                    Voir ma wishlist
                </button>
            </div>
        </div>
    </div>
</template>
