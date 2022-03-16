<template>

    <div class="m-2 bg-blue-500 rounded-xl relative text-white shadow">
        <img class="relative object-cover w-full h-full rounded-xl" :src="card_img" alt="">
        <div class="w-full px-8 absolute top-8">

            <!--USER NAME & COMPANY LOGO-->
            <div class="flex justify-between">

                <div>
                    <p class="font-light">Name</p>
                    <p class="tracking-widest font-bold">
                        {{ user.pax_name }}
                    </p>
                </div>

                <img class="w-28 h-auto" :src="short_logo" alt=""/>

            </div>

            <!--CARD NUMBER-->
            <div class="pt-4">
                <p class="font-light">Master Number</p>
                <p class="tracking-more-wider font-bold">
                    {{ passDetails.ms_qr_no }}
                </p>
            </div>

            <template class="pt-4" v-if="!isSv">
                <div class="flex justify-between">

                    <div>
                        <p class="font-light">Source</p>
                        <p class="tracking-widest font-bold">
                            {{ passDetails.source }}
                        </p>
                    </div>

                    <div>
                        <p class="font-light">Destination</p>
                        <p class="tracking-widest font-bold">
                            {{ passDetails.destination }}
                        </p>
                    </div>

                </div>
            </template>

            <div class="pt-2">
                <div class="flex justify-between">

                    <!--BALANCE-->
                    <div>
                        <p class="font-light text-xs">{{ isSv ? 'Balance' : 'Balance Trips' }}</p>
                        <p class="tracking-wider text-sm font-bold">
                            {{
                                isSv ?
                                    (balance === 0 ? 'fetching ..' : '₹ ' + balance)
                                    :
                                    (balance === 0 ? 'fetching ..' : balance + ' Trips')
                            }}
                        </p>
                    </div>

                    <!--EXP DATE-->
                    <div>
                        <p class="font-light text-xs">Expiry</p>
                        <p class="tracking-wider text-sm font-bold">
                            {{ new Date(passDetails.ms_qr_exp).toLocaleDateString() }}
                        </p>
                    </div>

                    <!--RELOAD-->
                    <div class="flex flex-row-reverse" v-if="isSv">
                        <ReloadButton
                            :type="'button'"
                            :is-disabled="isLoading"
                            :is-loading="isLoading"
                            :title="'Reload Pass'"
                            v-on:click="svReloadStatus"
                        />
                    </div>

                    <div class="flex flex-row-reverse" v-if="!isSv">
                        <ReloadButton
                            :type="'button'"
                            :is-disabled="isLoading"
                            :is-loading="isLoading"
                            :title="'Reload Pass'"
                            v-on:click="tpReloadStatus"
                        />
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>
<script>

import {Link} from '@inertiajs/inertia-vue3';
import axios from "axios";
import ReloadButton from "./Component/ReloadButton";

export default {
    name: "Card",
    props: {
        passDetails: Object,
        user: Object,
        isSv: Boolean,
        balance: Number,
    },
    components: {
        ReloadButton,
        Link
    },
    data() {
        return {
            short_logo: '/img/logo_short.png',
            card_img: '/img/card-bg.png',
            isLoading: false
        }
    },
    methods: {
        svReloadStatus: async function () {
            console.log("hello")
            this.isLoading = true
            const res = await axios.get('/sv/reload/status/' + this.passDetails.sale_or_no)
            const data = res.data
            if (data.status) this.onSuccess(data)
            else this.onFailure(data)
        },
        tpReloadStatus: async function () {
            console.log("hello")
            this.isLoading = true
            const res = await axios.get('/tp/reload/status/' + this.passDetails.sale_or_no)
            const data = res.data
            if (data.status) this.onSuccess(data)
            else this.onFailure(data)
        },
        onSuccess: function (data) {
            if (this.isSv)
            {
                this.$inertia.get('/sv/reload/' + this.passDetails.sale_or_no)
                this.isLoading = false;
            }
            else
            {
                this.$inertia.get('/tp/reload/' + this.passDetails.sale_or_no)
                this.isLoading = false;
            }
        },
        onFailure: function (data) {
            console.log(data)
            this.$swal.fire({
                icon: 'error',
                title: 'Failed to reload pass !',
                text: 'Please complete active trip to reload !',
                confirmButtonText: 'Okay',
            })
            this.isLoading = false;
        }
    }
}
</script>

<style scoped>

</style>
