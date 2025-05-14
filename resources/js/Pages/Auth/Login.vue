<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
    invitation_token:
        new URLSearchParams(window.location.search).get("invitation_token") ||
        null,
});

const submit = async () => {
    const token = form.invitation_token;

    if (token) {
        await router.post(route("invitations.storeTokenInSession"), { token });
    }

    form.post(route("login"));
};
</script>

<template>
    <div
        class="w-full min-h-screen bg-fixed bg-cover bg-center relative"
        style="background-image: url(/storage/images/tirage.jpg)"
    >
        <!-- Filtre flou et assombrissement doux -->
        <div
            class="min-h-screen backdrop-blur-sm bg-white/60 flex items-center justify-center px-4 py-24"
        >
            <!-- Formulaire -->
            <div
                class="relative z-10 bg-[#E3EFFD] border-[15px] border-[#a7befe] rounded-[20px] shadow-xl p-6 w-full max-w-sm"
            >
                <h1 class="text-3xl font-bold text-blue-900 text-center mb-6">
                    Connexion
                </h1>

                <form @submit.prevent="submit" class="space-y-4">
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

                    <div class="flex flex-col items-start space-y-2">
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
                        >
                            Mot de passe oublié ?
                        </a>
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
    </div>
</template>
