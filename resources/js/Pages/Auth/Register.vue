<script setup>
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    salutations: Array,
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    salutation_id: "", // ← maintenant basé sur l’id
});

const submit = () => {
    form.post(route("register"), {
        onSuccess: () => {
            router.visit(route("post.register.redirect"));
        },
    });
};
</script>

<template>
    <div class="w-full min-h-screen bg-fixed bg-cover bg-center relative"
        style="background-image: url(/storage/images/adraw.jpg)">
        <!-- Filtre flou et assombrissement doux -->
        <div class="min-h-screen backdrop-blur-sm bg-white/60 flex items-center justify-center px-4 py-24">
            <div class="bg-[#E3EFFD] border-[15px] border-[#a7befe] rounded-[20px] shadow-xl p-6 w-full max-w-sm">
                <h1 class="text-3xl font-bold text-blue-900 text-center mb-6">
                    Inscription
                </h1>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="salutation_id" class="block font-semibold mb-1 text-blue-900">
                            Civilité
                        </label>
                        <select id="salutation_id" v-model="form.salutation_id" required
                            class="w-full px-4 py-2 rounded-xl border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option disabled value="">
                                -- Sélectionner --
                            </option>
                            <option v-for="item in salutations" :key="item.id" :value="item.id">
                                {{ item.salutation_value }}
                            </option>
                        </select>
                        <div v-if="form.errors.salutation_id" class="text-red-600 text-sm mt-1">
                            {{ form.errors.salutation_id }}
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block font-semibold mb-1 text-blue-900">Nom</label>
                        <input id="name" type="text" v-model="form.name" autocomplete="name" required
                            class="w-full px-4 py-2 rounded-xl border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block font-semibold mb-1 text-blue-900">Adresse email</label>
                        <input id="email" type="email" v-model="form.email" autocomplete="username" required
                            class="w-full px-4 py-2 rounded-xl border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block font-semibold mb-1 text-blue-900">Mot de passe</label>
                        <input id="password" type="password" v-model="form.password" autocomplete="new-password"
                            required
                            class="w-full px-4 py-2 rounded-xl border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block font-semibold mb-1 text-blue-900">Confirmation</label>
                        <input id="password_confirmation" type="password" v-model="form.password_confirmation"
                            autocomplete="new-password" required
                            class="w-full px-4 py-2 rounded-xl border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-2 rounded-xl hover:bg-blue-700">
                        S'inscrire
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
