<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });
</script>

<template>
    <div class="min-h-screen bg-[#E3EFFD] p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Comparaison des couleurs</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Partie gauche : proposition Tailwind "classique" -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-700">Palette Tailwind Utility</h2>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full">Bouton
                    principal</button>
                <p class="text-blue-700 underline">Lien ou appel à l'action</p>
                <span class="bg-teal-400 text-teal-900 px-3 py-1 rounded-full text-sm">Succès / Réservé</span>
                <div class="bg-red-400 text-red-900 p-3 rounded">Message d'erreur</div>
                <div class="bg-indigo-200 text-indigo-800 p-3 rounded">Alerte / Info douce</div>
            </div>

            <!-- Partie droite : ta palette personnalisée -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-700">Palette personnelle douce</h2>
                <button class="bg-[#FBE844] text-black px-4 py-2 rounded-full">Bouton pastel</button>
                <p class="text-[#A5E1E9] underline">Lien pastel</p>
                <span class="bg-[#D6EDB9] text-black px-3 py-1 rounded-full text-sm">Succès / Réservé</span>
                <div class="bg-[#FAC9DC] text-black p-3 rounded">Message doux d'erreur</div>
                <div class="bg-[#FFC19A] text-black p-3 rounded">Alerte douce</div>
            </div>

        </div>
    </div>
</template>
