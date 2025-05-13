<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    assignments: Array,
    originalInput: Array,
});

const page = usePage();

const flash = computed(() => page.props.flash ?? {});
const old = computed(() => page.props.old ?? {});

const names = ref(old.value.names ?? props.originalInput ?? [""]);

function addNameField() {
    names.value.push("");
}

function removeNameField(index) {
    names.value.splice(index, 1);
}

function submit() {
    // Étape 1 : nettoyage (trim + filtres)
    const trimmed = names.value.map((n) => n.trim());
    const nonEmpty = trimmed.filter((n) => n !== "");
    const unique = Array.from(new Set(nonEmpty));

    // Étape 2 : mettre à jour visuellement le formulaire aussi
    names.value = unique.length > 0 ? unique : [""];

    // Étape 3 : soumission
    router.post(route("draw.store"), {
        names: unique,
    });
}
</script>

<template>
    <div
        class="min-h-screen bg-[#D6E9FC] py-10 px-4 flex flex-col items-center"
    >
        <!-- Message d'erreur -->
        <div
            v-if="flash.error"
            class="bg-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 font-semibold shadow"
        >
            {{ flash.error }}
        </div>

        <div
            class="w-full max-w-2xl bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6"
        >
            <h1 class="text-2xl font-bold text-blue-900 mb-6">
                🎲 Tirage au sort - sans compte
            </h1>

            <!-- Formulaire -->
            <div
                v-for="(name, index) in names"
                :key="index"
                class="flex items-center mb-2 gap-2"
            >
                <input
                    v-model="names[index]"
                    type="text"
                    placeholder="Nom du participant"
                    class="w-full px-4 py-2 rounded border border-blue-300 focus:outline-none focus:ring"
                />
                <button
                    v-if="names.length > 1"
                    @click="removeNameField(index)"
                    class="text-red-500 hover:text-red-700 font-bold text-xl"
                    title="Supprimer ce champ"
                >
                    &minus;
                </button>
            </div>

            <button
                @click="addNameField"
                class="text-blue-600 font-semibold hover:underline mb-4"
            >
                + Ajouter un nom
            </button>

            <button
                @click="submit"
                class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700 w-full"
            >
                Lancer le tirage
            </button>
        </div>

        <!-- Résultat -->
        <div
            v-if="assignments"
            class="mt-10 w-full max-w-2xl bg-white rounded-xl shadow p-6 border border-green-400"
        >
            <h2 class="text-xl font-bold text-green-800 mb-4">
                Résultats du tirage 🎉
            </h2>
            <ul class="space-y-2">
                <li
                    v-for="(pair, index) in assignments"
                    :key="index"
                    class="text-gray-800"
                >
                    <strong>{{ pair.from }}</strong> offre un cadeau à
                    <strong>{{ pair.to }}</strong>
                </li>
            </ul>
            <div class="flex justify-end gap-4 mt-6">
                <button
                    @click="submit"
                    class="bg-indigo-500 text-white font-semibold px-4 py-2 rounded-xl hover:bg-indigo-600"
                >
                    Refaire le tirage
                </button>

                <button
                    @click="router.visit(route('draw.index'))"
                    class="bg-indigo-500 text-white font-semibold px-4 py-2 rounded-xl hover:bg-indigo-600"
                >
                    Réinitialiser le tirage
                </button>
            </div>
        </div>
    </div>
</template>
