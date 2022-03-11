<template>
    <nav-bar />

    <card
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
            <a :href="'/gra/' + trip.sl_qr_no">
                <div class="bg-blue-500 text-center p-2 rounded-b-lg border border-blue-500 text-gray-50 font-bold mt-2 py-2 hover:bg-blue-700">
                    <i class="fa-solid fa-circle-info mx-1"></i>
                    UNABLE TO EXIT
                </div>
            </a>
        </div>
    </div>

    <anchor-button
        :title="'GENERATE TRIP'"
        :url="'/tp/trip/' + pass.sale_or_no"
        :type="'button'"
        v-if="!trip"
    />
    <AnchorButton
        :title="'REFUND PASS'"
        :url="'/refund/' + pass.sale_or_no + '/info'"
        :type="'button'"
        v-if="!trip"
    />
</template>

<script>

import QRCodeVue3 from "qrcode-vue3";
import axios from "axios";

export default {

    props: {
        user: Object,
        pass: Object,
        trip: Object,
    },

    data() {
        return {
            balance: 0
        }
    },

    name: "Dashboard",

    components: {AnchorButton, NavBar, Card, QRCodeVue3},

    async mounted() {
        const res = await axios.get('/tp/status/' + this.pass.ms_qr_no);
        const data = res.data;
        this.balance = data.data.balanceTrip;
    }

}
</script>

<style scoped>

</style>
