<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    wishlists: Array
})

const form = useForm({
    wishlist_id: '',
    name: '',
    description: '',
    image: null,
    purchase_url: '',
})

const previewUrl = ref(null)

function previewImage(e) {
    const file = e.target.files[0]
    form.image = file

    if (file && file.type.startsWith('image/')) {
        previewUrl.value = URL.createObjectURL(file)
    }
}


function submit() {
    form.post(route('gifts.store'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="w-full min-h-screen bg-[#D6E9FC] py-10 px-6 flex flex-col items-center">
        <div class="not-prose max-w-xl w-full bg-[#E3EFFD] p-6 rounded-xl shadow border border-blue-300">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Ajouter un cadeau</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-blue-900 mb-1">Wishlist</label>
                    <select v-model="form.wishlist_id" class="w-full border p-2 rounded">
                        <option disabled value="">-- Sélectionner une wishlist --</option>
                        <option v-for="w in props.wishlists" :key="w.id" :value="w.id">
                            {{ w.title }}
                        </option>
                    </select>
                    <div v-if="form.errors.wishlist_id" class="text-red-600 text-sm">{{ form.errors.wishlist_id }}</div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-blue-900 mb-1">Nom du cadeau</label>
                    <input type="text" v-model="form.name" class="w-full border p-2 rounded" />
                    <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-blue-900 mb-1">Description</label>
                    <textarea v-model="form.description" class="w-full border p-2 rounded"></textarea>
                    <div v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-blue-900 mb-1">Image</label>
                    <input type="file" @change="previewImage" class="w-full border p-2 rounded" />
                    <div v-if="form.errors.image" class="text-red-600 text-sm">{{ form.errors.image }}</div>
                    <img v-if="previewUrl" :src="previewUrl" class="w-32 h-32 object-cover rounded-xl mt-2" />
                </div>


                <div>
                    <label class="block text-sm font-bold text-blue-900 mb-1">Lien d’achat (facultatif)</label>
                    <input type="url" v-model="form.purchase_url" class="w-full border p-2 rounded" />
                    <div v-if="form.errors.purchase_url" class="text-red-600 text-sm">{{ form.errors.purchase_url }}
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700">
                        Ajouter le cadeau
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
