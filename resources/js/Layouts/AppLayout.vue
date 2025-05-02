<script setup>
  import { ref, onMounted, nextTick, watch } from 'vue'
  import { router, usePage, Head } from '@inertiajs/vue3'

  const page = usePage()
  defineProps({ title: String })

  const user = page.props.auth.user
  const isAdmin = user?.role === 'admin'

  const isSidebarOpen = ref(false)

  const logout = () => router.post(route('logout'))

  const handleNavigation = routeName => {
    router.visit(route(routeName))
    if (window.innerWidth < 768) {
      isSidebarOpen.value = false
    }
  }

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
  watch(
    () => [page.props.title, page.props.favicon],
    () => {
      setTimeout(() => {
        updateTitle()
        updateFavicon()
      }, 100)
    }
  )
</script>

<template>
  <div class="relative h-screen overflow-hidden">
    <!-- Bouton menu mobile -->
    <button
      @click="isSidebarOpen = !isSidebarOpen"
      class="md:hidden fixed top-4 left-4 z-50 bg-[#2DD4BF] text-white p-2 rounded-lg shadow"
    >
      ☰
    </button>

    <!-- Overlay semi-transparent -->
    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 md:hidden"
      @click="isSidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 h-screen overflow-y-auto bg-[#C7D2FE] text-[#3730A3] p-4 z-50 flex flex-col transition-transform duration-300 ease-in-out',
        isSidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full',
        'md:translate-x-0 md:w-64',
      ]"
    >
      <!-- Logo -->
      <div class="mb-6">
        <img
          src="/storage/images/favicon.ico"
          alt="Logo"
          class="block mt-6 ml-3 h-12 w-auto cursor-pointer"
          @click="handleNavigation('dashboard')"
        />
      </div>

      <hr class="border-[#F87171] my-4 mb-10 mt-5" />

      <!-- Liens de navigation -->
      <nav class="flex-grow space-y-4">
        <div
          class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
          :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('wishlists.*') }"
          @click="handleNavigation('wishlists.index')"
        >
          Mes listes
        </div>

        <div
          class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
          :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('gifts.index') }"
          @click="handleNavigation('gifts.index')"
        >
          Mes cadeaux
        </div>

        <div
          class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
          :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('events.*') }"
          @click="handleNavigation('events.index')"
        >
          Événements
        </div>

        <!-- Liens Admin -->
        <hr v-if="isAdmin" class="border-t-4 border-[#F87171] border-double my-6 w-full" />

        <div
          v-if="isAdmin"
          class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
          :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('users.index') }"
          @click="handleNavigation('users.index')"
        >
          Utilisateurs
        </div>

        <div
          v-if="isAdmin"
          class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
          :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('documents.index') }"
          @click="handleNavigation('documents.index')"
        >
          Documents légaux
        </div>

        <div
          v-if="isAdmin"
          class="block text-lg font-semibold py-2 px-4 rounded-lg hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
          :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('images.index') }"
          @click="handleNavigation('images.index')"
        >
          Images par défaut
        </div>
      </nav>

      <hr class="border-[#F87171] my-4 mt-4" />
      <form @submit.prevent="logout">
        <button
          class="block w-full font-semibold text-left py-2 px-4 rounded hover:text-blue-800 hover:bg-[#F87171]"
        >
          Déconnexion
        </button>
      </form>

      <hr class="border-[#F87171] my-4 mt-4" />
      <div
        class="block w-full font-semibold text-left py-2 px-4 rounded hover:text-blue-800 hover:bg-[#E3EFFD] cursor-pointer"
        :class="{ 'bg-[#2DD4BF] text-blue-800': route().current('legal.mentions') }"
        @click="handleNavigation('legal.mentions')"
      >
        Mentions légales
      </div>
    </aside>

    <!-- Contenu principal -->
    <div
      :class="[
        'h-screen overflow-auto z-10 bg-transparent relative transition-all duration-300 ease-in-out',
        isSidebarOpen ? 'ml-64' : 'ml-0',
        !isSidebarOpen && 'md:ml-64',
      ]"
    >
      <Head :title="title" />

      <!-- Flash Messages -->
      <div
        v-if="$page.props.flash?.success"
        class="bg-teal-200 text-teal-900 font-semibold px-4 py-2 rounded mb-4 border border-teal-400 m-4"
      >
        {{ $page.props.flash.success }}
      </div>
      <div
        v-if="$page.props.flash?.error"
        class="bg-red-400 text-white px-4 py-2 rounded mb-4 border border-red-600 m-4"
      >
        {{ $page.props.flash.error }}
      </div>

      <!-- Slot principal -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
