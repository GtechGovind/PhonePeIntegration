<template>

    <nav-bar />
    <hero />

    <div v-if="isPendingPayment">
        Loading please wait ....
    </div>

    <div v-else>
        <order-handler :stations="stations" />
    </div>

</template>

<script>

import axios from "axios";
import OrderHandler from "./components/OrderHandler";
import NavBar from "../../../shared/NavBar";
import Hero from "../../../shared/Hero";

export default {
    name: "Order",
    components: {Hero, NavBar, OrderHandler},
    props: {
        stations: Array
    },
    data() {
        return {
            isPendingPayment: false
        }
    },
    mounted() {
        this.paymentCheck()
    },
    methods: {

        /* PAYMENT CHECK */
        paymentCheck: async function () {
            const response = await axios.get('/ticket/order/pending')
            const data = await response.data
            this.isPendingPayment = data.isPendingPayment
        }

    }
}

</script>

<style scoped>

</style>
