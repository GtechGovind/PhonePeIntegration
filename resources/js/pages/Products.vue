<template>
    <NavBar/>
    <Hero/>
    <div class="bg-white cursor-pointer rounded shadow hover:bg-gray-50 m-3 border" v-for="product in products" :key="product.id">
        <Link :href="product.url" class="appearance-none">
            <div class="grid grid-cols-6">
                <div class="grid grid-rows-2 border-r">
                    <div class="row-span-2 text-center">
                        <div class="grid content-center h-full">
                            <div class="flex justify-center">
                                <img :src="product.img" class="w-10 rounded-t-lg" alt="QR Ticket">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-rows-2 col-span-4 p-3">
                    <div class="text-xl text-gray-600 font-semibold">
                        {{ product.name }}
                    </div>
                    <div class="text-xs content-center h-full mt-2">
                        {{ product.description }}
                    </div>
                </div>
                <div class="grid grid-rows-2 border-blue-400 rounded-r">
                    <div class="row-span-2 text-center">
                        <div class="grid content-center h-full">
                            <div class="flex justify-center">
                                <i class="fa-solid fa-angle-right fa-2xl text-gray-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Link>
    </div>
    <div class="container mx-auo">
        <div class="h-full my-10 grid grid-rows-1">
            <div class="mx-auto flex items-center">
                <img :src="coBrand" class="h-24" alt="logo">
            </div>
        </div>
    </div>
</template>

<script>

import Hero from "../Shared/Hero";
import {Link} from '@inertiajs/inertia-vue3'
import NavBar from "../Shared/NavBar";
import Button from "../Shared/Component/Button";
export default {

    props: {
        products: Array
    },

    name: "Products",

    components: {
        NeedHelpModel,
        NeedHelp,
        Button,
        NavBar,
        Hero,
        Link
    },

    data() {
        return {
            isLoading: false,
            isDisabled: false,
            coBrand: '/img/atek_logo.png',
        }
    },
}
</script>

<style scoped>

</style>
