<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout });

document.title = "Wishlist - Liste de souhaits";

const props = defineProps({
    wishlist: Object,
    allGifts: Array,
    linkedGiftIds: Array,
});

const form = useForm({
    title: props.wishlist.title,
    description: props.wishlist.description,
    selectedGifts: [...props.linkedGiftIds],
});

function submit() {
    form.put(route("wishlists.update", props.wishlist.id));
}
</script>

<template>

    <Head title="Wishlist - Liste de souhaits" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex justify-center items-start">
        <div class="not-prose max-w-xl w-full bg-[#E3EFFD] p-6 rounded-xl shadow border border-blue-300">
            <h1 class="text-3xl font-bold text-blue-900 mb-6 text-center">
                Modifier la wishlist
            </h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Titre -->
                <div>
                    <label class="block font-semibold text-blue-800 mb-1">Titre</label>
                    <input v-model="form.title" type="text" class="w-full border rounded p-2" />
                    <div v-if="form.errors.title" class="text-red-600 text-sm">
                        {{ form.errors.title }}
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-semibold text-blue-800 mb-1">Description</label>
                    <textarea v-model="form.description" class="w-full border rounded p-2"></textarea>
                    <div v-if="form.errors.description" class="text-red-600 text-sm">
                        {{ form.errors.description }}
                    </div>
                </div>

                <!-- Cadeaux -->
                <div>
                    <label class="block font-semibold text-blue-800 mb-1">Cadeaux associés</label>

                    <div v-if="props.allGifts.length > 0"
                        class="max-h-48 overflow-auto space-y-1 p-2 bg-white border rounded">
                        <label v-for="gift in props.allGifts" :key="gift.id"
                            class="flex items-center gap-3 text-blue-900">
                            <input type="checkbox" :value="gift.id" v-model="form.selectedGifts"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
                            <img v-if="gift.image" :src="'/storage/' + gift.image"
                                class="w-10 h-10 object-cover rounded" />
                            <span>{{ gift.name }}</span>
                        </label>
                    </div>

                    <div v-else class="text-sm italic text-gray-600 mt-1">
                        Aucun cadeau disponible pour associer.
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
