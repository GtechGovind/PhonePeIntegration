<template>

    <NavBar/>
    <Hero/>

    <RecentTicket v-if="recentOrders.length > 0" :recent-tickets="recentOrders"/>

    <TransitionGroup>
        <UpcomingTicket v-if="upcomingOrders.length > 0" :upcoming-tickets="upcomingOrders" class="mt-5"/>
    </TransitionGroup>

    <TransitionGroup>
        <div v-if="upcomingOrders.length === 0"
             class="relative rounded-lg m-2 bg-white">
            <div class="grid w-full content-center p-7">
                <div class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">No Upcoming Orders to show ....</div>
            </div>
        </div>
    </TransitionGroup>

    <Button
        :type="'button'"
        :title="upcomingOrders.length > 1 ? 'Only two orders are allowed at a time!' : 'Book Ticket'"
        :is-loading="isLoading"
        :is-disabled="isDisabled"
        v-on:click="newOrder"
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
