<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout });

document.title = "Wishlist - Liste de souhaits";

const props = defineProps({
    eventId: [String, Number, null],
    gifts: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    title: "",
    description: "",
    event_id: props.eventId,
    selectedGifts: [], // cadeaux cochés
});

function submit() {
    form.post(route("wishlists.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>

    <Head title="Wishlist - Liste de souhaits" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex justify-center items-start">
        <input type="hidden" name="event_id" :value="form.event_id" />

        <div class="not-prose max-w-xl w-full bg-[#E3EFFD] p-6 rounded-xl shadow border border-blue-300">
            <h1 class="text-3xl font-bold text-blue-900 mb-6 text-center">
                Créer une wishlist
            </h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Titre -->
                <div>
                    <label class="block font-semibold text-blue-800 mb-1">Titre de la wishlist</label>
                    <input v-model="form.title" type="text" class="w-full border rounded p-2"
                        placeholder="Liste pour mon anniversaire..." />
                    <div v-if="form.errors.title" class="text-red-600 text-sm">
                        {{ form.errors.title }}
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-semibold text-blue-800 mb-1">Description</label>
                    <textarea v-model="form.description" class="w-full border rounded p-2"
                        placeholder="Ex. Quelques idées de cadeaux..."></textarea>
                    <div v-if="form.errors.description" class="text-red-600 text-sm">
                        {{ form.errors.description }}
                    </div>
                </div>

                <!-- Ajout de cadeaux existants -->
                <div>
                    <label class="block font-semibold text-blue-800 mb-1">Ajouter des cadeaux existants</label>

                    <div v-if="props.gifts.length > 0"
                        class="max-h-48 overflow-auto space-y-2 p-2 bg-white border rounded">
                        <label v-for="gift in props.gifts" :key="gift.id" class="flex items-center gap-3 text-blue-900">
                            <input type="checkbox" :value="gift.id" v-model="form.selectedGifts"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded" />

                            <!-- Miniature -->
                            <img v-if="gift.image" :src="'/storage/' + gift.image" alt="Image du cadeau"
                                class="w-10 h-10 object-cover rounded" />

                            <!-- Nom du cadeau -->
                            <span>{{ gift.name }}</span>
                        </label>
                    </div>

                    <div v-else class="text-sm italic text-gray-600 mt-1">
                        Aucun cadeau existant à proposer.
                    </div>
                </div>

                <!-- Bouton -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
                        :disabled="form.processing">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
