<script setup>
  defineProps({
    show: Boolean,
    title: String,
    message: String,
  })

  defineEmits(['close'])
</script>

<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/30 backdrop-blur-md flex items-center justify-center z-50"
  >
    <div class="bg-[#D6E9FC] p-6 rounded-xl shadow-xl max-w-md w-full text-blue-900">
      <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
      <p class="mb-6">{{ message }}</p>

      <div class="flex justify-end">
        <button
          @click="$emit('close')"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          OK
        </button>
      </div>
    </div>
  </div>
</template>
