<template>

    <div v-if="isPendingPayment">

    </div>

    <div v-else>
        <TransitionGroup>
            <OrderHandler :stations="stations" />
        </TransitionGroup>
    </div>

</template>

<script>

import OrderHandler from './Components/OrderHandler'
import axios from "axios";

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
        OrderHandler
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
