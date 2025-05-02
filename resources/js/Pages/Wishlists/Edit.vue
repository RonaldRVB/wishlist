<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue'
  import { useForm, router } from '@inertiajs/vue3'

  defineOptions({ layout: AppLayout })

  const props = defineProps({
    wishlist: Object,
  })

  const form = useForm({
    description: props.wishlist.description || '',
    _method: 'put',
  })

  const submit = () => {
    form.post(route('wishlists.update', props.wishlist.id), {
      onSuccess: () => {
        router.visit(route('wishlists.index'))
      },
    })
  }
</script>

<template>
  <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
    <div class="not-prose max-w-4xl w-full">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Modifier la wishlist</h1>
        <button
          @click="router.visit(route('wishlists.index'))"
          class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700"
        >
          Retour
        </button>
      </div>

      <form
        @submit.prevent="submit"
        class="bg-[#E3EFFD] p-6 rounded-xl shadow border border-blue-300 space-y-6"
      >
        <!-- Titre affiché en lecture seule -->
        <div>
          <label class="font-semibold text-blue-900 block mb-1">Titre</label>
          <input
            type="text"
            :value="wishlist.title"
            disabled
            class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 text-gray-700 cursor-not-allowed"
          />
        </div>

        <!-- Description -->
        <div>
          <label class="font-semibold text-blue-900 block mb-1">Description</label>
          <textarea
            v-model="form.description"
            class="w-full rounded-lg border-gray-300 shadow-sm"
            rows="4"
            placeholder="Ajoute une description pour ta wishlist (facultatif)"
          ></textarea>
          <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">
            {{ form.errors.description }}
          </div>
        </div>

        <!-- Bouton -->
        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-blue-700"
            :disabled="form.processing"
          >
            Enregistrer les modifications
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
