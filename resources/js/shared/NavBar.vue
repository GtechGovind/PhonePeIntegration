<template>
    <div class="border rounded shadow-sm bg-white mx-2 mb-3">
        <div class="grid grid-cols-5 m-2 p-2">
            <div class="text-left" v-on:click="onBackButtonPress">
                <i class="fa-solid fa-chevron-left fa-xl"></i>
            </div>
            <div class="text-center col-span-3 font-semibold text-gray-700 text-xl">
                <Link :href="home">Mumbai Metro One</Link>
            </div>
            <button class="text-right" v-on:click="showMenuItems()">
                <i class="fa-solid fa-bars fa-xl"></i>
            </button>
        </div>
    </div>

    <div v-if="showMenu" class="m-2 bg-white border">
        <ul class="py-1">
            <li>
                <Link :href="home" class="block bg-gray-50 m-2 p-2 text-sm text-gray-700 rounded hover:bg-gray-100">
                    Home
                </Link>
            </li>
            <li>
                <Link :href="terms" class="block bg-gray-50 m-2 p-2 text-sm rounded text-gray-700 hover:bg-gray-100">
                    Terms & Conditions
                </Link>
            </li>
        </ul>
    </div>

</template>

<script>

import {Link} from '@inertiajs/inertia-vue3'

export default {

    name: "NavBar",
    components: {Link},
    data() {
        return {
            showMenu: false,
            terms: '/terms',
            home: '',
            location: window.location.pathname
        }
    },
    methods: {
        showMenuItems: function () {
            this.showMenu = !this.showMenu;
        },
        onBackButtonPress: function () {
            if (this.location.includes('ticket')) {
                this.home = '/ticket/dashboard'
            } else if (this.location.includes('SV')) {
                this.home = '/SV/dashboard'
            } else if (this.location.includes('tp')) {
                this.home = '/tp/dashboard'
            }
        }
    }
}

</script>

<style scoped>

</style>
