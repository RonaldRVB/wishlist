<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-16 px-6 flex flex-col items-center"
    >
        <div
            class="max-w-4xl w-full bg-[#E3EFFD] p-8 rounded-xl shadow border border-blue-300 text-blue-900 text-justify leading-relaxed"
        >
            <h1 class="text-3xl font-bold mb-6 text-center">
                Bienvenue sur ton espace personnel 🎉
            </h1>

            <p class="mb-4 text-lg">
                Grâce à cette plateforme, tu peux organiser des événements,
                créer des listes de souhaits et inviter les personnes de ton
                choix à y participer. Voici ce que tu peux faire :
            </p>

            <ul class="list-disc pl-6 mb-6 text-lg space-y-2">
                <li>
                    <strong>Créer et gérer</strong> tes wishlists pour chaque
                    occasion (Noël, anniversaire, etc.)
                </li>
                <li>
                    <strong>Associer</strong> une ou plusieurs wishlists à un
                    événement
                </li>
                <li>
                    <strong>Inviter des participants</strong> par email avec ou
                    sans création de compte
                </li>
                <li>
                    <strong>Lancer un tirage au sort</strong> automatique entre
                    les invités d’un événement
                </li>
                <li>
                    <strong>Partager un lien public</strong> entre les invités
                    pour leur permettre de consulter ou réserver un cadeau
                </li>
            </ul>

            <p class="text-lg">
                Tu peux commencer depuis le menu de gauche en créant tes
                premiers cadeaux puis ta première wishlist et enfin créer un
                nouvel événement.
            </p>
        </div>
    </div>
</template>
