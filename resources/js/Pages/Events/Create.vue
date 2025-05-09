<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    defaultImages: Array,
    hasWishlist: Boolean,
    wishlists: Array,
});

const page = usePage();
const flashError = ref(page.props.flash?.error || null);

if (flashError.value) {
    // On "nettoie" le message immédiatement après affichage
    setTimeout(() => {
        page.props.flash.error = null;
        flashError.value = null;
    }, 0);
}

const customImagePreview = ref(null);

const handleCustomImage = (e) => {
    const file = e.target.files[0];

    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
        form.errors.custom_image =
            "L’image est trop lourde. Elle ne peut pas dépasser 5 Mo.";
        e.target.value = "";
        form.custom_image = null;
        customImagePreview.value = null;
        return;
    }

    // 👇 Test supplémentaire : charger dans un objet <img> pour valider le fichier
    const img = new Image();
    const url = URL.createObjectURL(file);

    img.onload = () => {
        // ✅ L’image est valide
        form.custom_image = file;
        customImagePreview.value = url;
        if (form.errors.custom_image) delete form.errors.custom_image;
    };

    img.onerror = () => {
        // ❌ Le fichier ne peut pas être interprété comme image
        form.errors.custom_image =
            "Le fichier sélectionné n’est pas une image valide.";
        e.target.value = "";
        form.custom_image = null;
        customImagePreview.value = null;
        URL.revokeObjectURL(url);
    };

    // Lance la vérification en essayant de charger l’image
    img.src = url;
};

const form = useForm({
    title: "",
    description: "",
    event_date: "",
    is_public: false,
    default_image_id: null,
    custom_image: null,
    emails: [""], // emails pour les invitations
    end_date: null,
    is_collaborative: false,
    wishlist_id: "",
});

const selectedImageId = computed(() => form.default_image_id);

function addEmailField() {
    form.emails.push("");
}

function removeEmailField(index) {
    if (index !== 0) {
        form.emails.splice(index, 1);
    }
}

const submit = () => {
    form.post(route("events.store"), {
        forceFormData: true,
        onSuccess: () => {
            router.visit(route("events.index"));
        },
    });
};
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center"
    >
        <div class="not-prose max-w-4xl w-full">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-blue-900">
                    Créer un événement
                </h1>

                <button
                    @click="router.visit(route('events.index'))"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
                >
                    Retour
                </button>
            </div>

            <div
                class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 space-y-6"
            >
                <form @submit.prevent="submit" class="space-y-6 w-full">
                    <!-- Titre -->
                    <div>
                        <label class="block font-semibold text-blue-900 mb-1"
                            >Titre de l’événement</label
                        >
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full rounded px-4 py-2 border border-gray-300"
                            required
                        />
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block font-semibold text-blue-900 mb-1"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            class="w-full rounded px-4 py-2 border border-gray-300"
                        ></textarea>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block font-semibold text-blue-900 mb-1"
                            >Date de l’événement</label
                        >
                        <input
                            v-model="form.event_date"
                            type="date"
                            class="w-full rounded px-4 py-2 border border-gray-300"
                            required
                        />
                    </div>

                    <!-- Image personnalisée -->

                    <!-- Type d’événement -->
                    <div>
                        <label class="block font-semibold text-blue-900 mb-2"
                            >Type d’événement</label
                        >
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input
                                    type="radio"
                                    :checked="form.is_collaborative === false"
                                    @change="form.is_collaborative = false"
                                    class="mr-2"
                                />
                                Événement personnel (ma wishlist uniquement)
                            </label>
                            <label class="inline-flex items-center">
                                <input
                                    type="radio"
                                    :checked="form.is_collaborative === true"
                                    @change="form.is_collaborative = true"
                                    class="mr-2"
                                />
                                Événement collaboratif (plusieurs wishlists
                                possibles)
                            </label>
                        </div>
                    </div>

                    <!-- <p class="mt-4 text-sm text-gray-700">
                        <strong>Debug :</strong> is_collaborative =
                        {{ form.is_collaborative }}
                    </p> -->

                    <!-- Date de fin (facultative) -->
                    <div>
                        <label class="font-semibold text-blue-900"
                            >Date de fin (facultative)</label
                        >
                        <input
                            type="date"
                            v-model="form.end_date"
                            class="w-full rounded-lg border-gray-300 mt-1 shadow-sm"
                        />
                    </div>

                    <div>
                        <label class="block font-semibold text-blue-900 mb-1"
                            >Image personnalisée (upload)</label
                        >
                        <input
                            type="file"
                            @change="handleCustomImage"
                            class="w-full"
                            accept="image/*"
                        />

                        <div
                            v-if="form.errors.custom_image"
                            class="text-red-700 text-sm mt-1"
                        >
                            {{ form.errors.custom_image }}
                        </div>

                        <div
                            v-if="customImagePreview"
                            class="mt-2 flex justify-center"
                        >
                            <img
                                :src="customImagePreview"
                                alt="Aperçu"
                                class="w-32 h-auto rounded shadow border"
                            />
                        </div>
                    </div>

                    <!-- Image par défaut -->
                    <div>
                        <label class="block font-semibold text-blue-900 mb-2"
                            >Choisissez une image par défaut :</label
                        >
                        <div class="flex flex-wrap gap-4">
                            <div
                                v-for="img in defaultImages"
                                :key="img.id"
                                class="cursor-pointer rounded-lg overflow-hidden shadow-sm transition-all duration-200"
                                :class="
                                    form.default_image_id === img.id
                                        ? 'border-2 border-blue-600 ring-2 ring-blue-300'
                                        : 'border-2 border-gray-300'
                                "
                                @click="form.default_image_id = img.id"
                                style="width: 96px"
                            >
                                <img
                                    :src="'/storage/' + img.path"
                                    :alt="img.label"
                                    class="w-full h-auto max-h-24 object-contain"
                                />
                            </div>
                        </div>

                        <p
                            v-if="!form.default_image_id"
                            class="text-sm text-gray-600 mt-2 italic"
                        >
                            Aucune image par défaut sélectionnée
                        </p>
                    </div>

                    <!-- Sélection de la wishlist à associer -->
                    <div>
                        <label class="block font-semibold text-blue-900 mb-1"
                            >Associer une wishlist à cet événement</label
                        >
                        <select
                            v-model="form.wishlist_id"
                            class="w-full rounded border border-gray-300 px-4 py-2"
                            required
                        >
                            <option disabled value="">
                                -- Sélectionner une wishlist --
                            </option>
                            <option
                                v-for="wishlist in props.wishlists"
                                :key="wishlist.id"
                                :value="wishlist.id"
                            >
                                {{ wishlist.title }}
                            </option>
                        </select>
                    </div>

                    <!-- Invitations -->
                    <div>
                        <label class="block font-semibold text-blue-900 mb-2"
                            >Inviter des participants (optionnel)</label
                        >

                        <div
                            v-for="(email, index) in form.emails"
                            :key="index"
                            class="flex items-center space-x-2 mb-2"
                        >
                            <input
                                type="email"
                                v-model="form.emails[index]"
                                placeholder="exemple@mail.com"
                                class="w-full rounded border border-gray-300 p-2 shadow-sm"
                            />

                            <button
                                v-if="index !== 0"
                                type="button"
                                @click="removeEmailField(index)"
                                class="text-red-600 hover:text-red-800 font-bold text-lg"
                            >
                                &times;
                            </button>
                        </div>

                        <button
                            type="button"
                            @click="addEmailField"
                            class="bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600 mt-2"
                        >
                            + Ajouter un champ
                        </button>
                    </div>

                    <!-- Bouton submit -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
                            :disabled="form.processing"
                        >
                            Créer l’événement
                        </button>
                    </div>

                    <div
                        v-if="flashError"
                        class="bg-red-100 text-red-900 border border-red-300 px-4 py-2 rounded-xl mb-4"
                    >
                        {{ flashError }}
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
