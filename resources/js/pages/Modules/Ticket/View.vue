<template>
    <nav-bar />
    <hero />
    <div class="grid grid-rows-1 m-2" v-if="type === 1">
        <button
            class="bg-blue-500 text-gray-50 font-bold px-2 py-2 rounded w-full">
            Outward
        </button>
    </div>
    <div class="grid grid-rows-1 grid-cols-2 m-2" v-if="type === 2">
        <button
            class="rounded-l"
            :class="showSingle ? 'btn_selected' : 'btn_not_selected'"
            v-on:click="showUpwardTicket">
            Outward
        </button>
        <button
            class="rounded-r"
            :class="!showSingle ? 'btn_selected' : 'btn_not_selected'"
            v-on:click="showReturnTicket">
            Return
        </button>
    </div>

    <TicketSwiper v-on:need-help="needHelpCallback(sl_qr_no)" :ticket="upwardTicket" :order_id="order_id" v-if="showSingle"/>
    <TicketSwiper :ticket="returnTicket" :order_id="order_id" v-if="!showSingle"/>

    <div class="border rounded-lg border-dashed border-3 border-blue-700 bg-white m-2">
        <div class="grid grid-cols-2 px-3 pt-3">
            <div class="text-left text-xs font-bold text-gray-400">From Station</div>
            <div class="text-right text-xs font-bold text-gray-400">To Station</div>
        </div>
        <div class="grid grid-cols-5 px-3 pt-1">
            <div class="text-left font-bold text-gray-700 col-span-2">{{ upwardTicket[0]['source'] }}</div>
            <div class="text-center"><i class="fas fa-long-arrow-alt-right"></i></div>
            <div class="text-right font-bold text-gray-700 col-span-2">{{ upwardTicket[0]['destination'] }}</div>
        </div>
        <div class="grid grid-rows-2 grid-cols-6  mt-2 border-t">
            <div class="row-span-2 col-span-2 m-1 border-r">
                <div class="flex items-center row-span-2 h-full">
                    <div class="mx-auto text-3xl font-bold text-gray-700">₹{{ upwardTicket[0]['total_price'] }}</div>
                </div>
            </div>
            <div class="grid grid-rows-4 m-3 row-span-2 col-span-4">
                <div class="text-left text-xs font-bold text-gray-400">Booking Date</div>
                <div class="text-left font-bold text-gray-700">{{ upwardTicket[0]['txn_date'] }}</div>
                <div class="text-left text-xs font-bold text-gray-400">Expiry Date</div>
                <div class="text-left font-bold text-gray-700">{{ upwardTicket[0]['sl_qr_exp'] }}</div>
            </div>
        </div>
    </div>
</template>

<script>

import NavBar from "../../../Shared/NavBar";
import Hero from "../../../Shared/Hero";
import TicketSwiper from "./Components/TicketSwiper";

export default {

    props: {
        type: Number,
        order_id: String,
        upwardTicket: Array,
        returnTicket: Array,
    },

    name: "View",

    components: {
        TicketSwiper,
        Hero,
        NavBar,
    },

    data() {
        return {
            showSingle: true,
        }
    },

    methods: {
        showUpwardTicket: function () {
            this.showSingle = true
        },
        showReturnTicket: function () {
            this.showSingle = false
        },
        needHelpCallback: function (sl_qr_no) {
            alert("hi " + sl_qr_no)
        }
    },
}

</script>

<style scoped>

</style>
