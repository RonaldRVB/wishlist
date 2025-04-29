<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    gift: Object,
});

const previewImage = ref(null);

function handleImageChange(event) {
    const file = event.target.files[0];
    form.image = file;

    if (file) {
        previewImage.value = URL.createObjectURL(file);
    } else {
        previewImage.value = null;
    }
}

const form = useForm({
    name: props.gift.name || "",
    description: props.gift.description || "",
    url: props.gift.url || "",
    image: null, // pour le nouveau fichier uploadé
});

function submit() {
    form.post(route("gifts.update", props.gift.id), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route("gifts.index"));
        },
        onError: (errors) => {
            console.error("Erreurs lors de l'enregistrement:", errors);
        },
    });
}
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center"
    >
        <div class="not-prose max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Modifier le cadeau
                </h1>

                <button
                    @click="router.visit(route('gifts.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                >
                    Retour
                </button>
            </div>

            <div
                class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 flex flex-col items-center"
            >
                <form @submit.prevent="submit" class="w-full space-y-6">
                    <div>
                        <label
                            for="name"
                            class="block mb-2 text-sm font-bold text-blue-900"
                            >Nom du cadeau</label
                        >
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="w-full border-gray-300 rounded-xl"
                        />
                    </div>

                    <div>
                        <label
                            for="description"
                            class="block mb-2 text-sm font-bold text-blue-900"
                            >Description</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="w-full border-gray-300 rounded-xl"
                        ></textarea>
                    </div>

                    <div>
                        <label
                            for="url"
                            class="block mb-2 text-sm font-bold text-blue-900"
                            >Lien externe (URL)</label
                        >
                        <input
                            id="url"
                            v-model="form.url"
                            type="url"
                            class="w-full border-gray-300 rounded-xl"
                        />
                    </div>
                    <div
                        v-if="gift.image"
                        class="mb-6 w-full flex flex-col items-start"
                    >
                        <p class="text-sm font-semibold text-blue-900 mb-2">
                            Image actuelle :
                        </p>
                        <img
                            :src="'/storage/' + gift.image"
                            alt="Image actuelle du cadeau"
                            class="w-32 h-32 object-cover rounded-xl border border-blue-300"
                        />
                    </div>

                    <div>
                        <label
                            for="image"
                            class="block mb-2 text-sm font-bold text-blue-900"
                            >Nouvelle image (facultatif)</label
                        >
                        <div
                            v-if="form.image"
                            class="mt-4 w-full flex flex-col items-start"
                        >
                            <p class="text-sm font-semibold text-blue-900 mb-2">
                                Nouvelle image sélectionnée :
                            </p>
                            <img
                                :src="previewImage"
                                alt="Nouvelle image"
                                class="w-32 h-32 object-cover rounded-xl border mb-4 border-blue-300"
                            />
                        </div>

                        <input
                            id="image"
                            type="file"
                            @input="handleImageChange"
                            class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                        />
                        <!-- Affichage de l'erreur -->
                        <div
                            v-if="form.errors.image"
                            class="text-red-600 text-sm mt-2"
                        >
                            {{ form.errors.image }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
                        >
                            Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
