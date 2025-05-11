<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";

defineOptions({ layout: AppLayout });

const props = defineProps({
    eventId: [String, Number, null],
});

const form = useForm({
    title: "",
    description: "",
    event_id: props.eventId,
});

function submit() {
    // console.log('Form data :', form)
    form.post(route("wishlists.store"), {
        preserveScroll: true,
        onSuccess: () => {
            // console.log('Succès !')
            form.reset();
        },
        onError: () => {
            // console.log('Erreur détectée !', form.errors)
        },
    });
}
</script>

<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex justify-center items-start"
    >
        <input type="hidden" name="event_id" :value="form.event_id" />

        <div
            class="not-prose max-w-xl w-full bg-[#E3EFFD] p-6 rounded-xl shadow border border-blue-300"
        >
            <h1 class="text-3xl font-bold text-blue-900 mb-6 text-center">
                Créer une wishlist
            </h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block font-semibold text-blue-800 mb-1"
                        >Titre de la wishlist</label
                    >
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full border rounded p-2"
                        placeholder="Liste pour mon anniversaire..."
                    />
                    <div v-if="form.errors.title" class="text-red-600 text-sm">
                        {{ form.errors.title }}
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-blue-800 mb-1"
                        >Description</label
                    >
                    <textarea
                        v-model="form.description"
                        class="w-full border rounded p-2"
                        placeholder="Ex. Quelques idées de cadeaux..."
                    ></textarea>
                    <div
                        v-if="form.errors.description"
                        class="text-red-600 text-sm"
                    >
                        {{ form.errors.description }}
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
                        :disabled="form.processing"
                    >
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
