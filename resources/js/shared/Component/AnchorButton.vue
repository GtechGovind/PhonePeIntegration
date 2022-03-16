<template>
    <div class="p-2">
        <Link
            :disabled="disabled"
            :href="url"
            :as="type"
            type="button"
            :class="disabled ? 'bg-gray-500' : 'bg-blue-500'"
            class="w-full h-12 px-6 text-gray-50 font-bold rounded-lg text-xl z-10">
            {{ title }}
        </Link>
    </div>
</template>

<script>

import {Link} from '@inertiajs/inertia-vue3'

export default {
    props: {
        title: String,
        url: String,
        type: String,
        disabled: false
    },
    name: "AnchorButton",
    components: {
        Link
    }
}
</script>

<style scoped>

</style>
