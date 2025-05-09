<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

defineProps({
    event: Object,
    wishlists: Array,
});
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold text-blue-800 mb-4">
            Wishlists pour l'événement : {{ event.title }}
        </h1>

        <ul class="list-disc pl-6">
            <li v-for="wishlist in wishlists" :key="wishlist.id">
                {{ wishlist.title }}
            </li>
        </ul>
    </div>
</template>
