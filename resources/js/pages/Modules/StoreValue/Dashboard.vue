<template>

    <nav-bar/>

    <card
        :pass-details="pass"
        :user="user"
        :isSv="true"
        :balance="balance"
    />

    <div v-if="trip">
        <div class="text-center m-2 border bg-white rounded-lg">
            <div class="text-center p-2">
                <div class="flex justify-center m-1">
                    <QRCodeVue3
                        class="w-3/4"
                        :value="trip['qr_data']"
                        :cornersSquareOptions="{ type: 'square' }"
                        :qr-options="{ typeNumber: 0, mode: 'Byte', errorCorrectionLevel: 'L' }"
                        :dots-options="{ type: 'square', color: '#1f1f1f' }"
                        :backgroundOptions="{ color: '#ffffff' }"
                    />
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                    {{ trip.sl_qr_no }}
                </span>
            </div>
            <button v-on:click="getGraInfo()"
                    class="bg-blue-500 text-center p-3 rounded-b-lg text-gray-50 mt-2 w-full">
                <i class="fa-solid fa-circle-info mx-1"></i> NEED HELP
            </button>
        </div>
    </div>

    <GraModel
        v-if="trip"
        :slave_id="trip.sl_qr_no"
        :stations="stations"
    />

    <RecentTicket
        v-if="canRefund"
        :order_id="pass.sale_or_no"
    />

    <PassButton
        v-if="!trip"
        :is-disabled="isLoadingGenTrip"
        :is-loading="isLoadingGenTrip"
        :type="'button'"
        :title="'GENERATE TRIP'"
        v-on:click="genTrip"
    />

    <PassButton
        :is-disabled="isLoadingRefund"
        :is-loading="isLoadingRefund"
        :type="'button'"
        :title="'REFUND PASS'"
        v-on:click="refundPass"
    />

</template>

<script setup>

import NavBar from "../../../Shared/NavBar";
import Card from "../../../Shared/Card";
import GraModel from "../../../Shared/Model/GraModel";
import RecentTicket from "../Ticket/Components/RecentTicket";
import PassButton from "../../../Shared/Component/PassButton";
import {onMounted, ref} from "vue";
import axios from "axios";

const props = defineProps({
    user: Object,
    pass: Object,
    trip: Object,
    stations: Array
})

// VARIABLES
let balance = ref()
let isLoadingGenTrip = ref()
let isLoadingRefund = ref()
let canRefund = ref()

// METHODS
const genTrip = () => {
    isLoadingGenTrip = true
    this.$inertia.get('/sv/trip/' + props.pass.sale_or_no)
}
const getGraInfo = () => {
    toggleModal('gra-help', true)
}
const refundPass = async () => {
    isLoadingRefund = true
    const res = await axios.get('/refund/' + props.pass.sale_or_no)
    const data = await res.data
    if (data.status) {
        isLoadingRefund = false
        canRefund = true
        toggleModal('refund-help', true)
    } else {
        isLoadingRefund = false
        this.$swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: data.error,
        })
    }
}
const updatePass = async () => {
    const res = await axios.get('/sv/status/' + props.pass.ms_qr_no);
    const data = res.data;
    balance = data.data.balance;
    this.$inertia.reload()
}

// LIFECYCLE
onMounted(() => {
    updatePass()
})

</script>

<style scoped>

</style>
