<template>

    <NavBar/>
    <Hero/>

    <RecentTicket v-if="recentOrders.length > 0" :recent-tickets="recentOrders"/>

    <TransitionGroup>
        <UpcomingTicket v-if="upcomingOrders.length > 0" :upcoming-tickets="upcomingOrders" class="mt-5"/>
    </TransitionGroup>

    <div :class="upcomingOrders.length < 1 ? 'absolute bottom-0 left-0 w-full' : ''">
        <Button
            :type="'button'"
            :title="upcomingOrders.length > 1 ? 'Only two orders are allowed at a time!' : 'Book Ticket'"
            :is-loading="isLoading"
            :is-disabled="upcomingOrders.length > 1"
            v-on:click="newOrder"
        />
    </div>

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
import Button from "../../../Shared/Component/Button";

export default {
    name: "Dashboard",
    data() {
        return {
            isLoading: false,
            isDisabled: false,
        }
    },
    components: {Button, AnchorButton, UpcomingTicket, RecentTicket, Hero, NavBar, Link},
    props: {
        user: Object,
        upcomingOrders: Array,
        recentOrders: Array
    },
    methods: {
        updateDashboard: async function () {
            const res = await axios.get('/ticket/status');
            const data = res.data
            if (data.status) {
                Inertia.reload({only: ['upcomingOrders', 'recentOrders']})
                this.isLoading = false
                this.isDisabled = false
            }
        },
        newOrder: function () {
            this.isLoading = true
            this.isDisabled = true
            this.$inertia.visit('/ticket/order')
        }
    },
    mounted() {
        this.updateDashboard()
    }
}
</script>

<style scoped>

</style>
