<script setup>
const tests = [
    {
        feature: "Authentification (login, logout, inscription)",
        expected: "L’utilisateur peut se connecter, se déconnecter",
        tested: true,
        note: "",
    },
    {
        feature: "Acceptation des mentions légales à l’inscription",
        expected: "Case obligatoire à cocher",
        tested: false,
        note: "Prévu mais pas encore mis en place",
    },
    {
        feature: "Statut utilisateur 'Banni' pour l'exclusion d'un utilisateur",
        expected: "Un utilisateur banni ne peut plus se connecter",
        tested: false,
        note: "Prévu mais pas encore mis en place",
    },
    {
        feature: "Création de wishlists",
        expected: "Un utilisateur peut créer une ou plusieurs wishlists",
        tested: true,
        note: "",
    },
    {
        feature: "Ajout, édition et suppression de cadeaux",
        expected: "Fonctionnement complet avec image, description, URL",
        tested: true,
        note: "",
    },
    {
        feature: "Déplacement de cadeaux entre wishlists",
        expected: "Cadeau transférable d’une wishlist à une autre",
        tested: true,
        note: "",
    },
    {
        feature: "Réservation de cadeaux",
        expected: "Un cadeau réservé s’affiche comme réservé",
        tested: true,
        note: "Synchronisé dans toutes les wishlists liées",
    },
    {
        feature: "Création d’événements",
        expected: "Un utilisateur peut créer un événement et y lier des wishlists",
        tested: true,
        note: "",
    },
    {
        feature: "Attribution d’images personnalisées aux événements",
        expected: "Image téléversée ou image par défaut selon le type",
        tested: true,
        note: "",
    },
    {
        feature: "Envoi d’invitations par mail avec token unique",
        expected: "L’invité reçoit un lien personnel vers l’événement",
        tested: true,
        note: "Envoi réel configuré avec o2switch",
    },
    {
        feature: "Acceptation / refus d’invitation",
        expected: "L’invité choisit sans devoir créer de compte",
        tested: true,
        note: "",
    },
    {
        feature: "Tirage au sort",
        expected: "Le tirage prend uniquement les invités ayant accepté",
        tested: true,
        note: "",
    },
    {
        feature: "Tableau de bord admin",
        expected: "Liste utilisateurs, rôles, statuts",
        tested: true,
        note: "",
    },
    {
        feature: "Modification des mentions légales depuis l’admin",
        expected: "Texte éditable",
        tested: true,
        note: "",
    },
    {
        feature: "Ajout/modification des images par défaut (admin)",
        expected: "Accessible depuis l’interface admin",
        tested: true,
        note: "",
    },
];
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-4 flex flex-col items-center">
        <div class="not-prose max-w-7xl w-full">
            <h1 class="text-3xl font-bold text-blue-900 mb-6 text-center">
                Tests fonctionnels du site
            </h1>

            <div class="overflow-x-auto bg-[#E3EFFD] border border-blue-300 rounded-xl shadow">
                <table class="w-full table-auto text-left">
                    <thead class="bg-teal-200 text-black">
                        <tr>
                            <th class="px-4 py-2">Fonctionnalité</th>
                            <th class="px-4 py-2">Résultat attendu</th>
                            <th class="px-4 py-2 text-center">Testé</th>
                            <th class="px-4 py-2">Remarque</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(test, index) in tests" :key="index" class="border-t border-green-500">
                            <td class="px-4 py-2 font-bold">{{ test.feature }}</td>
                            <td class="px-4 py-2 font-bold">{{ test.expected }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="text-green-700 font-bold" v-if="test.tested">✔</span>
                                <span class="text-red-600 font-bold" v-else>✖</span>
                            </td>
                            <td class="px-4 py-2 italic font-bold text-sm">
                                {{ test.note || '—' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
