<template>
    <nav-bar />

    <Card
        :pass-details="pass"
        :user="user"
        :isSv="false"
        :balance="balance"
    />

    <div v-if="trip">
        <div class="text-center m-2 border bg-white rounded-lg">
            <div class="text-center p-2">
                <div class="flex justify-center m-1">
                    <QRCodeVue3
                        class="w-3/4"
                        :value="trip.qr_data"
                        :cornersSquareOptions="{ type: 'square' }"
                    />
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                    {{ trip.sl_qr_no }}
                </span>
            </div>
            <button v-on:click="getGraInfo(trip.sl_qr_no)" class="bg-blue-500 text-center p-2 rounded-b-lg text-gray-50 mt-2 w-full">
                <i class="fa-solid fa-circle-info mx-1"></i> NEED HELP
            </button>
        </div>
    </div>

    <GraModel
        :slave_id="trip.sl_qr_no"
        :stations="stations"
    />

    <RefundModel :order_id="trip.sale_or_no" />

    <PassButton
        v-if="!trip"
        :is-disabled="isLoading"
        :is-loading="isLoading"
        :type="'button'"
        :title="'GENERATE TRIP'"
        v-on:click="genTrip"
    />

</template>

<script>

import QRCodeVue3 from "qrcode-vue3";
import axios from "axios";
import NavBar from "../../../Shared/NavBar";
import Card from "../../../Shared/Card";
import GraModel from "../../../Shared/Model/GraModel";
import RefundModel from "../../../Shared/Model/RefundModel";
import PassButton from "../../../Shared/Component/PassButton";

export default {

    props: {
        user: Object,
        pass: Object,
        trip: Object,
    },

    data() {
        return {
            balance: 0,
            isLoading: false
        }
    },

    name: "Dashboard",

    components: {PassButton, RefundModel, GraModel, Card, NavBar, QRCodeVue3},

    async mounted() {
        const res = await axios.get('/tp/status/' + this.pass.ms_qr_no);
        const data = res.data;
        this.balance = data.data.balanceTrip;
    },

    methods: {
        genTrip: async function() {
            this.isLoading = true
            this.$inertia.get('/tp/trip/' + this.pass.sale_or_no)
        },
        getGraInfo: function () {
            toggleModal('need-help', true)
        }
    }
}
</script>

<style scoped>

</style>
