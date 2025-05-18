<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

document.title = "Wishlist - Images";

const props = defineProps({
    defaultImage: Object,
});

const form = useForm({
    label: props.defaultImage.label,
    image_path: props.defaultImage.path,
    _method: "put",
});

function submit() {
    form.post(route("images.update", props.defaultImage.id));
}
</script>

<template>

    <Head title="Wishlist - Images" />
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Modifier le titre
                </h1>

                <button @click="router.visit(route('images.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                    Retour
                </button>
            </div>

            <div class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 flex flex-col items-center w-full">
                <form @submit.prevent="submit" class="space-y-4 w-full">
                    <!-- Champ titre -->
                    <div>
                        <label for="label" class="block text-sm font-bold text-gray-700 mb-1">Titre de l’image</label>
                        <input type="text" id="label" v-model="form.label"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        <div v-if="form.errors.label" class="text-red-600 text-sm mt-2">
                            {{ form.errors.label }}
                        </div>
                    </div>

                    <!-- Aperçu -->
                    <div class="mt-4">
                        <p class="text-gray-700 italic mb-1">Aperçu :</p>
                        <img :src="'/storage/' + defaultImage.path" :alt="defaultImage.label"
                            class="max-h-40 rounded shadow border" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
                            :disabled="form.processing">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
