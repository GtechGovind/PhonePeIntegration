<template>
    <div class="relative rounded-lg m-2 bg-white">
        <div class="grid w-full content-center p-3" >
            <img :src="logo" alt="..." class="mx-auto">
        </div>
    </div>
</template>

<script>
export default {
    name: "Hero",
    data() {
        return {
            logo: '/img/hero.png'
        }
    }
}
</script>

<style scoped>

</style>
