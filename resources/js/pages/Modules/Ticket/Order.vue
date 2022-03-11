<template>

    <nav-bar/>
    <hero/>

    <div v-if="isPendingPayment">

    </div>

    <div v-if="!isPendingPayment">
        <OrderHelper :stations="stations"/>
    </div>

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
            const response = await axios.get('/order/pending')
            const data = await response.data
            const {isPendingPayment} = data
            this.isPendingPayment = isPendingPayment
        }
    },

}
</script>

<style scoped>

</style>
