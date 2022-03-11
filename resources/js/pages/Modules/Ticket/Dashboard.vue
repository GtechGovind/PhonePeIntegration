<template>

    <NavBar/>
    <Hero/>

    <RecentTicket v-if="recentOrders.length > 0" :recent-tickets="recentOrders"/>

    <TransitionGroup>
        <UpcomingTicket v-if="upcomingOrders.length > 0" :upcoming-tickets="upcomingOrders" class="mt-5"/>
    </TransitionGroup>

    <AnchorButton
        :disabled="upcomingOrders.length > 1"
        :title="upcomingOrders.length > 1 ? 'Only two orders are allowed' : 'Book New Ticket'"
        :url="'/ticket/order'"
        :type="'button'"
    />

</template>

<script>

import NavBar from "../../../Shared/NavBar";
import Hero from "../../../Shared/Hero";
import RecentTicket from "./Components/RecentTicket";
import UpcomingTicket from "./Components/UpcomingTicket";
import {Link} from '@inertiajs/inertia-vue3'
import AnchorButton from "../../../Shared/Component/AnchorButton";
import axios from "axios";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Dashboard",
    components: {AnchorButton, UpcomingTicket, RecentTicket, Hero, NavBar, Link},
    props: {
        user: Object,
        upcomingOrders: Array,
        recentOrders: Array
    },
    methods: {
        updateDashboard: async  function () {
            const res = await axios.get('/ticket/status');
            const data = res.data
            if (data.status) {
                Inertia.reload({only: ['upcomingOrders', 'recentOrders']})
            }
        }
    },
    mounted() {
        this.updateDashboard()
    }

}
</script>

<style scoped>

</style>
