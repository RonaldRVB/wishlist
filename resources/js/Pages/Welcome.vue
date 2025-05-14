<script setup>
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import { onMounted, nextTick } from "vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    title: String,
});

const page = usePage();

// Gestion titre + favicon dynamique
const updateTitle = async () => {
    await nextTick();
    const pageTitle = page.props.title || "Wishlist";
    document.title = `${pageTitle}`;
};
const updateFavicon = async () => {
    await nextTick();
    const faviconPath = page.props.favicon || "/favicon.ico";
    let link = document.querySelector("link[rel~='icon']");
    if (!link) {
        link = document.createElement("link");
        link.rel = "icon";
        document.head.appendChild(link);
    }
    link.href = faviconPath;
};

onMounted(() => {
    updateTitle();
    updateFavicon();
});
</script>

<template>
    <Head :title="title" />

    <div
        class="relative min-h-screen bg-cover bg-center bg-fixed text-blue-900"
        style="background-image: url(&quot;/storage/images/accueil.jpg&quot;)"
    >
        <!-- Header -->
        <div class="absolute top-0 right-0 p-6 flex gap-4 z-10">
            <Link
                v-if="canLogin"
                :href="route('login')"
                class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-indigo-700 transition"
            >
                Log in
            </Link>

            <Link
                v-if="canRegister"
                :href="route('register')"
                class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-indigo-700 transition"
            >
                Register
            </Link>
        </div>

        <!-- Contenu principal -->
        <div class="min-h-screen backdrop-blur-sm bg-white/60 px-4 py-24">
            <div
                class="grid grid-cols-1 md:grid-cols-[1.3fr_1fr] gap-10 max-w-7xl mx-auto items-start"
            >
                <!-- Texte à gauche -->
                <div
                    class="bg-white/80 text-blue-900 p-8 rounded-xl shadow text-justify leading-relaxed max-w-2xl mx-auto"
                >
                    <h2 class="text-3xl font-bold mb-4">
                        Bienvenue sur Wishlist
                    </h2>
                    <p class="text-lg">
                        Notre plateforme te permet de créer et partager des
                        listes de souhaits, organiser des événements, inviter
                        des proches et même faire des tirages au sort anonymes
                        pour des échanges de cadeaux.
                        <br /><br />
                        Simple, élégant, sans stress.
                    </p>
                </div>

                <!-- Bloc tirage à droite -->
                <div
                    class="bg-[#E3EFFD] border-[6px] border-[#a7befe] p-6 rounded-xl shadow-xl text-justify leading-relaxed w-full max-w-md mx-auto"
                >
                    <h1 class="text-2xl font-bold mb-4">
                        🎲 Tirage au sort sans compte
                    </h1>
                    <p class="mb-6 text-base">
                        Cette fonctionnalité te permet de faire un tirage au
                        sort simple, sans compte, pour organiser un échange de
                        cadeaux (type Secret Santa). Saisis quelques prénoms,
                        clique sur <strong>"Lancer le tirage"</strong>
                        et découvre qui offre un cadeau à qui !
                    </p>

                    <div class="flex justify-end">
                        <button
                            @click="router.visit(route('draw.index'))"
                            class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-indigo-700 transition"
                        >
                            Accéder au tirage
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
