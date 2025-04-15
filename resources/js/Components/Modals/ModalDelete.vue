<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    show: Boolean,
    entity: Object,         // ton entité passée (ex: user, event, invitation, image)
    routeName: String,      // nom de la route à utiliser pour la suppression
    labelKey: {
        type: String,
        default: 'name',    // champ utilisé pour l'affichage
    },
    onClose: Function,
})

function deleteEntity() {
    if (props.entity && props.routeName) {
        router.delete(route(props.routeName, props.entity.id), {
            onFinish: () => props.onClose(),
        })
    }
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-md">
        <div class="bg-[#D6E9FC] text-blue-900 p-6 rounded-xl shadow-xl max-w-md w-full">
            <h2 class="text-lg p-3 bg-indigo-200 rounded-xl font-bold mb-4">Confirmer la suppression</h2>
            <p class="text-blue-900 font-bold mb-6">
                Es-tu sûr de vouloir supprimer
                <span v-if="entity?.[labelKey]">
                    : <br><br> <strong class="text-2xl font-bold">{{ entity[labelKey] }} ?</strong>
                </span>
                <span v-else>cet élément ?</span>
                <br><br>
                Cette action est
                <span class="text-lg italic text-red-400 font-bold">irréversible</span>.
            </p>

            <div class="flex justify-end space-x-4">
                <button @click="onClose" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Annuler
                </button>
                <button @click="deleteEntity" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</template>
