<template>

    <nav-bar/>
    <hero/>

    <template v-if="source">
        <OrderHelper :stations="stations" :source="source" :destination="destination"/>
    </template>

    <template v-else>
        <OrderHelper :stations="stations"/>
    </template>

</template>

<script>

import axios from "axios";
import NavBar from "../../../Shared/NavBar";
import Hero from "../../../Shared/Hero";
import OrderHelper from "./Components/OrderHelper";

export default {

    data() {
        return {
            isPendingPayment: false
        }
    },

    async mounted() {
        await this.checkIsPending()
    },

    props: {
        stations: Array,
        source: Number,
        destination: Number
    },

    name: "Order",

    components: {
        OrderHelper,
        Hero,
        NavBar
    },

    methods: {
        checkIsPending: async function () {
            const response = await axios.get('/ticket/order/pending')
            const data = await response.data
            const {isPendingPayment} = data
            this.isPendingPayment = isPendingPayment
        }
    },

}
</script>

<style scoped>

</style>
