<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout });

document.title = "Wishlist - Liste de souhaits";

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

    <Head title="Wishlist - Liste de souhaits" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full space-y-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Wishlists pour l'événement : {{ event.title }}
                </h1>

                <button @click="router.visit(route('invitations.mine'))"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl">
                    Retour à mes invitations
                </button>
            </div>

            <li v-for="wishlist in wishlists" :key="wishlist.id"
                class="flex items-center justify-between border rounded-xl p-4 shadow space-x-6 overflow-x-auto" :class="wishlist.user.id === event.user_id
                    ? 'bg-teal-100 border-teal-400'
                    : 'bg-white border-blue-300'
                    ">
                <!-- Image de l'événement -->
                <img :src="event.custom_image
                    ? '/storage/' + event.custom_image
                    : '/images/default-event.webp'
                    " alt="Miniature" class="w-16 h-16 rounded-lg object-cover shrink-0" />

                <!-- Nom utilisateur -->
                <p class="font-bold text-blue-900 whitespace-nowrap">
                    {{ wishlist.user.name }}
                </p>

                <!-- Texte -->
                <p class="text-sm italic text-blue-700 whitespace-nowrap">
                    a partagé une wishlist
                </p>

                <!-- Titre de la liste -->
                <p class="text-sm text-gray-800 font-semibold whitespace-nowrap">
                    "{{ wishlist.title }}"
                </p>

                <!-- Bouton -->
                <button @click="goToWishlist(wishlist.slug)"
                    class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded-xl whitespace-nowrap">
                    Voir
                </button>
            </li>

            <!-- ✅ Bloc 1 : création possible -->
            <div v-if="userWishlist === null && event.is_collaborative" class="text-center mt-8">
                <p class="text-blue-800 mb-4 font-semibold">
                    Tu n’as pas encore créé ta wishlist pour cet événement.
                </p>
                <button @click="createWishlistForEvent"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-xl font-bold">
                    + Créer ma wishlist
                </button>
            </div>

            <!-- ✅ Bloc 2 : l'utilisateur a déjà une wishlist -->
            <div v-if="userWishlist !== null" class="text-center mt-8">
                <p class="text-blue-800 mb-4">
                    Tu as déjà une wishlist liée à cet événement.
                </p>
                <button @click="goToWishlist(userWishlist.slug)"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold">
                    Voir ma wishlist
                </button>
            </div>
        </div>
    </div>
</template>
