<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue'

  defineOptions({ layout: AppLayout })

  const props = defineProps({
    wishlist: Object,
    gifts: Array,
  })
</script>

<template>
  <div class="w-full min-h-screen bg-[#D6E9FC] py-10">
    <div class="max-w-7xl mx-auto px-6">
      <h1 class="text-3xl font-bold text-blue-900 mb-6">
        {{ wishlist.title }}
      </h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div
          v-for="gift in gifts"
          :key="gift.id"
          class="bg-[#E3EFFD] border border-blue-300 p-4 rounded-2xl shadow-sm flex flex-col items-center"
        >
          <img
            v-if="gift.image"
            :src="'/storage/' + gift.image"
            :alt="gift.name"
            class="w-32 h-32 object-cover rounded-xl mb-3"
          />

          <h2 class="text-lg font-bold text-gray-900 mb-1 text-center">
            {{ gift.name }}
          </h2>

          <p class="text-gray-700 text-sm mb-3 text-center">
            {{ gift.description || '—' }}
          </p>

          <a
            v-if="gift.purchase_url"
            :href="gift.purchase_url"
            target="_blank"
            class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-full text-sm mb-2"
          >
            Voir le produit
          </a>

          <div
            v-if="gift.is_reserved"
            class="text-xs text-white bg-green-500 px-3 py-1 rounded-full shadow-sm"
          >
            Réservé
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
