<template>
    <div class="text-center m-2 border bg-white rounded-lg">
        <swiper :modules="modules" navigation="">
            <swiper-slide class="text-center" v-for="({id, qr_data, sl_qr_no}, index) in ticket" :key="id">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded pt-2">Passenger {{ index + 1 }}</span>
                <div class="flex justify-center m-1">
                    <QRCodeVue3
                        class="w-1/2"
                        :value="qr_data"
                        :cornersSquareOptions="{ type: 'square' }"
                        :qr-options="{ typeNumber: 0, mode: 'Byte', errorCorrectionLevel: 'L' }"
                        :dots-options="{ type: 'square', color: '#1f1f1f' }"
                        :backgroundOptions="{ color: '#ffffff' }"
                    />
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                    {{ sl_qr_no }}
                </span>
                <button v-on:click="NeedHelp(sl_qr_no)" class="bg-blue-500 text-center p-2 rounded-b-lg text-gray-50 mt-2 w-full">
                    <i class="fa-solid fa-circle-info mx-1"></i> NEED HELP
                </button>
            </swiper-slide>
        </swiper>
    </div>
</template>

<script>

import { Navigation, Pagination, Scrollbar, A11y } from 'swiper';
import {Swiper, SwiperSlide} from 'swiper/vue';
import QRCodeVue3 from "qrcode-vue3";
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';

export default {

    name: "TicketSwiper",

    props: {
        ticket: Array,
        order_id: String
    },

    components: {
        Swiper,
        SwiperSlide,
        QRCodeVue3
    },

    methods: {
        NeedHelp: function (NeedHelp) {
            alert("Hello" + NeedHelp)
        }
    },

    setup() {

        const onSlideChange = (swiper) => {
            console.log(swiper);
        };

        return {
            onSlideChange,
            modules: [Navigation, Pagination, Scrollbar, A11y],
        };
    },

}
</script>

<style scoped>

</style>
