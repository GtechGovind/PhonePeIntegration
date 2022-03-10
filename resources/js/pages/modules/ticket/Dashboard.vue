<template>

    <NavBar />
    <Hero />
    <Recent v-if="recentOrders.length > 0" :recent-tickets="recentOrders" />

    <transition-group>
        <Upcoming v-if="upcomingOrders.length > 0" :upcoming-tickets="upcomingOrders" class="mt-5" />
    </transition-group>

</template>

<script>

import NavBar from "../../../shared/NavBar";
import Hero from "../../../shared/Hero";
import Recent from "./components/Recent";
import Upcoming from "./components/Upcoming";
import {Inertia} from "@inertiajs/inertia";
import axios from "axios";

export default {
    components: {Upcoming, Recent, Hero, NavBar},
    props: {
        user: Object,
        upcomingOrders: Array,
        recentOrders: Array
    },
    name: "Dashboard",
    mounted() {
        this.updateDashboard()
    },
    methods: {
        updateDashboard: async function () {
            const res = await axios.get('/ticket/status');
            const data = res.data
            if (data.status) {
                Inertia.reload({only: ['upcomingOrders', 'recentOrders']})
            }
        }
    }
}
</script>

<style scoped>

</style>
