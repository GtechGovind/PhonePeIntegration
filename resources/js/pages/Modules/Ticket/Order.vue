<template>

    <nav-bar />
    <hero />

    <div v-if="isPendingPayment">
        <div>hello</div>
    </div>

    <div v-else>
        <TransitionGroup>
            <CreateOrder :stations="stations" />
        </TransitionGroup>
    </div>

</template>

<script>

import axios from "axios";
import NavBar from "../../../Shared/NavBar";
import Hero from "../../../Shared/Hero";
import CreateOrder from "./CreateOrder";

export default {

    data() {
        return {
            isPendingPayment: false
        }
    },

    mounted() {
        this.checkIsPending()
    },

    props: {
        stations: Array
    },

    name: "Order",

    components: {
        Hero,
        NavBar,
        CreateOrder
    },

    methods: {
        checkIsPending: async function () {
            const response = await axios.get('/order/pending')
            const data = await response.data
            const { isPendingPayment } = data
            this.isPendingPayment = isPendingPayment
        }
    },

}
</script>

<style scoped>

</style>
