<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue'
  import { router } from '@inertiajs/vue3'
  import { ref } from 'vue'
  import ModalDelete from '@/Components/Modals/ModalDelete.vue'
  import ModalInfo from '@/Components/Modals/ModalInfo.vue'

  defineOptions({ layout: AppLayout })

  // Props
  const props = defineProps({
    wishlists: Array,
  })

  // Modal suppression
  const showDeleteModal = ref(false)
  const wishlistToDelete = ref(null)

  // Modal info (liste perso non modifiable/supprimable)
  const showInfoModal = ref(false)
  const infoModalContent = ref({ title: '', message: '' })

  function openDeleteModal(wishlist) {
    if (wishlist.title === 'Ma liste personnelle') {
      infoModalContent.value = {
        title: 'Suppression impossible',
        message: 'La liste personnelle ne peut pas être supprimée.',
      }
      showInfoModal.value = true
    } else {
      wishlistToDelete.value = wishlist
      showDeleteModal.value = true
    }
  }

  function confirmDelete() {
    router.delete(route('wishlists.destroy', wishlistToDelete.value.id), {
      onSuccess: () => {
        showDeleteModal.value = false
        wishlistToDelete.value = null
      },
    })
  }

  function editWishlist(wishlist) {
    if (wishlist.title === 'Ma liste personnelle') {
      infoModalContent.value = {
        title: 'Modification impossible',
        message: 'La liste personnelle ne peut pas être modifiée.',
      }
      showInfoModal.value = true
    } else {
      router.visit(route('wishlists.edit', wishlist.id))
    }
  }

  function createNewWishlist() {
    router.visit(route('wishlists.create'))
  }
</script>

<template>
  <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
    <div class="max-w-5xl w-full">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Mes wishlists</h1>
        <button
          @click="createNewWishlist"
          class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
        >
          + Nouvelle liste
        </button>
      </div>

      <table class="bg-[#E3EFFD] shadow rounded-xl mx-auto overflow-hidden w-full">
        <thead class="bg-teal-200 text-black">
          <tr>
            <th class="text-left px-4 py-2">Nom</th>
            <th class="text-center px-4 py-2">Description</th>
            <th class="text-right px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="wishlist in wishlists"
            :key="wishlist.id"
            class="border-t border-green-500 hover:bg-blue-50 transition"
          >
            <!-- Titre -->
            <td class="px-4 py-2 font-bold text-blue-800 text-left">
              {{ wishlist.title }}
            </td>

            <!-- Description -->
            <td class="px-4 py-2 text-center text-gray-700">
              {{ wishlist.description || '—' }}
            </td>

            <!-- Actions -->
            <td class="px-4 py-2 text-right">
              <div class="flex justify-end space-x-4">
                <!-- Voir -->
                <button
                  @click="router.visit(route('wishlists.show', wishlist.id))"
                  class="text-blue-700 hover:text-indigo-800"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5
                      5-5 5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3
                      3-1.346 3-3-1.346-3-3-3z"
                    />
                  </svg>
                </button>

                <!-- Modifier -->
                <button @click="editWishlist(wishlist)" class="text-blue-700 hover:text-red-400">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M3 17.25V21h3.75l11.065-11.065-3.75-3.75L3
                      17.25zM21.41 6.34c.39-.39.39-1.02
                      0-1.41l-2.34-2.34a1.003 1.003 0 0 0-1.42
                      0l-1.83 1.83 3.75 3.75 1.84-1.83z"
                    />
                  </svg>
                </button>

                <!-- Supprimer -->
                <button
                  @click="openDeleteModal(wishlist)"
                  class="text-blue-700 hover:text-[#F87171]"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"
                    />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modals -->
    <ModalDelete
      v-if="showDeleteModal"
      :show="showDeleteModal"
      :entity="wishlistToDelete"
      routeName="wishlists.destroy"
      labelKey="title"
      @close="showDeleteModal = false"
      @confirm="confirmDelete"
    />

    <ModalInfo
      v-if="showInfoModal"
      :show="showInfoModal"
      :title="infoModalContent.title"
      :message="infoModalContent.message"
      @close="showInfoModal = false"
    />
  </div>
</template>
