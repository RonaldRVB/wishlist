<template>
    <div
        class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex items-center justify-center relative"
    >
        <!-- Image du cadeau en fond décalé -->
        <img
            src="npm run dev/images/gift-1.png"
            alt="Cadeau"
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-2/3 w-80 opacity-80 pointer-events-none z-0"
        />

        <!-- Formulaire -->
        <div
            class="relative z-10 bg-[#E3EFFD] border border-blue-300 rounded-xl shadow p-6 w-full max-w-md"
        >
            <h1 class="text-3xl font-bold text-blue-900 text-center mb-6">
                Connexion
            </h1>

            <form @submit.prevent="form.post(route('login'))" class="space-y-4">
                <div>
                    <label
                        for="email"
                        class="block font-semibold mb-1 text-blue-900"
                        >Adresse email</label
                    >
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        autocomplete="username"
                        class="w-full px-4 py-2 rounded-xl border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                    <div
                        v-if="form.errors.email"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.email }}
                    </div>
                </div>

                <div>
                    <label
                        for="password"
                        class="block font-semibold mb-1 text-blue-900"
                        >Mot de passe</label
                    >
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        autocomplete="current-password"
                        class="w-full px-4 py-2 rounded-xl border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                    <div
                        v-if="form.errors.password"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label
                        class="flex items-center space-x-2 text-sm text-blue-900"
                    >
                        <input
                            type="checkbox"
                            v-model="form.remember"
                            class="rounded border-blue-300"
                        />
                        <span>Se souvenir de moi</span>
                    </label>

                    <a
                        :href="route('password.request')"
                        class="text-blue-700 text-sm hover:underline"
                        >Mot de passe oublié ?</a
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-xl hover:bg-blue-700"
                >
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});
</script>
