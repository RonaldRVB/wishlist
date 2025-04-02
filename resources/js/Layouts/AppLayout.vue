<script setup>
import { ref, onMounted, nextTick, watch, computed } from 'vue'
import { router, usePage, Head } from '@inertiajs/vue3'

const page = usePage()

defineProps({ title: String })

const showingNavigationDropdown = ref(false)

const user = usePage().props.auth.user
const isAdmin = user?.role === 'admin'

const logout = () => router.post(route('logout'))

// Gestion titre + favicon dynamique
const updateTitle = async () => {
    await nextTick()
    const pageTitle = page.props.title || 'Wishlist'
    document.title = `${pageTitle}`
}
const updateFavicon = async () => {
    await nextTick()
    const faviconPath = page.props.favicon || '/favicon.ico'
    let link = document.querySelector("link[rel~='icon']")
    if (!link) {
        link = document.createElement('link')
        link.rel = 'icon'
        document.head.appendChild(link)
    }
    link.href = faviconPath
}
onMounted(() => {
    setTimeout(() => {
        updateTitle()
        updateFavicon()
    }, 100)
})
watch(() => [page.props.title, page.props.favicon], () => {
    setTimeout(() => {
        updateTitle()
        updateFavicon()
    }, 100)
})
</script>

<template>
    <div class="relative h-screen overflow-hidden">
        <!-- Barre latérale fixe -->
        <aside class="fixed top-0 left-0 h-screen w-64 bg-[#C7D2FE] text-[#3730A3] p-4 z-50 flex flex-col">
            <!-- Logo -->
            <div class="mb-6">
                <img id="app-logo" src="/storage/images/favicon.ico" alt="Logo"
                    class="block mt-6 ml-3 h-12 w-auto cursor-pointer" @click="router.visit(route('dashboard'))" />
            </div>

            <hr class="border-[#F87171] blue-300 my-4 mb-10 mt-5" />

            <!-- Navigation -->
            <nav class="flex-grow space-y-4">
                <div class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
                    :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('wishlists.*') }"
                    @click="router.visit(route('wishlists.index'))">
                    Mes listes
                </div>

                <div class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
                    :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('events.*') }"
                    @click="router.visit(route('events.index'))">
                    Événements
                </div>

                <!-- Liens Admin -->
                <div v-if="isAdmin"
                    class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
                    :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('users.index') }"
                    @click="router.visit(route('users.index'))">
                    Utilisateurs
                </div>

                <div v-if="isAdmin"
                    class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
                    :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('documents.index') }"
                    @click="router.visit(route('documents.index'))">
                    Documents légaux
                </div>

                <div v-if="isAdmin"
                    class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
                    :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('images.index') }"
                    @click="router.visit(route('images.index'))">
                    Images par défaut
                </div>











            </nav>

            <!-- Déconnexion -->
            <hr class="border-[#F87171] my-4 mt-4" />
            <form @submit.prevent="logout">
                <button
                    class="block w-full font-semibold text-left py-2 px-4 rounded hover:text-blue-800 hover:bg-[#F87171]">
                    Déconnexion
                </button>
            </form>

            <hr class="border-[#F87171] my-4 mt-4" />

            <div class="block w-full font-semibold text-left py-2 px-4 rounded hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
                :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('legal.mentions') }"
                @click="router.visit(route('legal.mentions'))">
                Mentions légales
            </div>
        </aside>

        <!-- Image de fond -->
        <!-- <div class="fixed inset-0 bg-cover bg-center bg-no-repeat z-[-10]"
            style="background-image: url('/storage/images/accueil-2.webp');">
        </div> -->

        <!-- Contenu principal -->
        <div class="ml-64 h-screen overflow-auto z-10 bg-transparent relative">

            <Head :title="title" />

            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success"
                class="bg-teal-200 text-teal-900 font-semibold px-4 py-2 rounded mb-4 border border-teal-400 m-4">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error"
                class="bg-red-400 text-white px-4 py-2 rounded mb-4 border border-red-600 m-4">
                {{ $page.props.flash.error }}
            </div>

            <!-- Contenu injecté -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
