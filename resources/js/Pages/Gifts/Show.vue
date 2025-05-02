<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue'
  import { router } from '@inertiajs/vue3'

  defineOptions({ layout: AppLayout })

  const props = defineProps({
    gift: Object,
    userWishlists: Array,
  })

  function toggleWishlist(wishlistId, isChecked) {
    const routeName = isChecked ? 'gifts.wishlists.attach' : 'gifts.wishlists.detach'

    router.post(
      route(routeName, { gift: props.gift.id }),
      { wishlist_id: wishlistId },
      {
        preserveScroll: true,
        preserveState: true,
        only: ['gift'],
      }
    )
  }
</script>

<template>
  <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
    <div class="not-prose max-w-4xl w-full">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Détail du cadeau</h1>

        <button
          @click="router.visit(route('gifts.index'))"
          class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
        >
          Retour
        </button>
      </div>

      <div class="bg-[#E3EFFD] rounded-xl shadow border border-blue-300 p-6 space-y-6">
        <!-- Image -->

        <div v-if="gift.image" class="text-center">
          <img
            :src="'/storage/' + gift.image"
            :alt="gift.name"
            class="max-h-60 mx-auto rounded-xl shadow"
          />
        </div>

        <!-- Infos -->

        <div>
          <h2 class="text-2xl font-bold text-blue-900 mb-2">{{ gift.name }}</h2>

          <p class="text-gray-700 mb-4">
            {{ gift.description || 'Aucune description.' }}
          </p>

          <div v-if="gift.purchase_url">
            <a
              :href="gift.purchase_url"
              target="_blank"
              class="text-blue-700 underline font-semibold hover:text-blue-900"
            >
              Voir le produit
            </a>
          </div>
        </div>

        <!-- Wishlists liées -->

        <div class="mt-8">
          <h2 class="text-xl font-bold text-blue-900 mb-2">Associer à des wishlists</h2>

          <div class="space-y-2">
            <div
              v-for="wishlist in userWishlists"
              :key="wishlist.id"
              class="flex items-center space-x-2"
            >
              <input
                type="checkbox"
                :id="'wishlist-' + wishlist.id"
                :checked="gift.wishlists.some(w => w.id === wishlist.id)"
                @change="toggleWishlist(wishlist.id, $event.target.checked)"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded"
              />

              <label :for="'wishlist-' + wishlist.id" class="text-gray-800">
                {{ wishlist.title }}
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
