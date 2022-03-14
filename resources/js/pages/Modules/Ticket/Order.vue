<template>

    <nav-bar/>
    <hero/>

    <OrderHelper :stations="stations"/>

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
        stations: Array
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
