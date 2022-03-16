<template>
    <div :class="isLoading ? '' : 'hidden'" class="overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center md:inset-0 h-modal sm:h-full" id="loading-model">
        <div class="relative px-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-6  pt-5 text-center">
                    <Spinner class="mx-auto mb-4 w-14 h-14"/>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Loading Please wait ...</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Spinner from "../Component/Spinner";

export default {

    name: "LoadingModel",

    components: {Spinner},

    props: {
        isLoading: {
            type: Boolean,
            default: false
        }
    }

}
</script>

<style scoped>

</style>
