<template>

    <nav-bar />

    <hero />

    <div class="bg-white m-2 p-3 shadow border text-center">
        <p class="font-bold text-lg text-gray-600">BUY NEW TRIP PASS</p>
    </div>



        <div class="container mx-auto rounded-lg">

            <div class="m-2 bg-white rounded border p-3">

                <!--SOURCE-->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Source</label>
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            v-model="tripPass.source_id" v-on:change="getFare">
                        <option disabled value="">Select source station</option>
                        <option
                            v-for="{ id, stn_id, stn_name } in stations"
                            :key="id"
                            :value="stn_id">
                            {{ stn_name }}
                        </option>
                    </select>
                    <div class="block mb-2 text-sm text-red-500" v-if="tripPass.errors.source_id">
                        {{ tripPass.errors.source_id }}
                    </div>
                </div>

                <!--DESTINATION-->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Destination</label>
                    <select
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        v-model="tripPass.destination_id" v-on:change="getFare">
                        <option disabled value="">Select destination station</option>
                        <option
                            v-for="{ id, stn_id, stn_name } in stations"
                            :key="id"
                            :value="stn_id">
                            {{ stn_name }}
                        </option>
                    </select>
                    <div class="block mb-2 text-sm text-red-500" v-if="tripPass.errors.destination_id">
                        {{ tripPass.errors.destination_id }}
                    </div>
                </div>

                <!--TYPE AND QUANTITY-->
                <div class="bg-blue-100 grid grid-cols-3 p-3 rounded-lg">
                    <div class="grid grid-row-2 pl-3">
                        <div class="text-xs text-blue-900">Trips</div>
                        <div class="font-semibold text-xl">{{ trips }}</div>
                    </div>
                    <div class="grid grid-row-2 pl-3">
                        <div class="text-xs text-blue-900">Validity</div>
                        <div class="font-semibold text-xl">{{ validity }} Days</div>
                    </div>
                    <div class="grid grid-row-2 pl-3">
                        <div class="text-xs text-blue-900">Fare</div>
                        <div class="font-semibold text-xl">₹ {{ tripPass.fare }}</div>
                    </div>
                </div>

            </div>

        </div>

        <Button v-on:click="" :title="'PROCEED TO PAY ₹ ' + tripPass.fare" />



</template>

<script>

import {useForm} from '@inertiajs/inertia-vue3'
import NavBar from "../../Shared/NavBar";
import Hero from "../../Shared/Hero";
import Chip from "../../Shared/Chip";
import Button from "../../Shared/Components/Button";
import axios from "axios";

export default {

    props: {
        stations: Array,
        trips: Number,
        validity: Number,
    },

    name: "Order",

    components: {Button, Hero, NavBar},

    data() {
        return {
            tripPass: useForm({
                source_id: '',
                destination_id: '',
                fare: 0,
                pass_id: "21"
            })
        }
    },

    methods: {
        getFare: async function () {

            const response = await axios.post('/api/get/fare', {
                "source": this.tripPass.source_id,
                "destination": this.tripPass.destination_id,
                "pass_id": this.tripPass.pass_id
            });

            let data = await response.data
            if (data.status) this.tripPass.fare = data.fare
            else console.log(data.errors)

        },


        genOrder: async function () {
            const response = await axios.post('/tp/create', this.tripPass)
            let data = await response.data
            if (data.status) this.onSuccess(data)
            else this.onFailure(data)
        },

        onSuccess: function (data) {
            const { redirectUrl } = data
            window.location.href = redirectUrl
        },

        onFailure: function (data) {
            const { errors } = data
            this.errors = errors
        },

    },





    async mounted() {

        const res = await axios.get('/sv/canIssuePass')
        const data = await res.data

        if (!data.status) {

            this.$swal.fire({
                icon: 'error',
                title: data.error,
                text: 'Please contact Mumbai Metro One for assistance !',
                confirmButtonText: 'Okay',
            }).then((res) => {
                if (res.isConfirmed) {
                    this.$inertia.get("/products")
                }
            })
        }
    }

}
</script>

<style scoped>

</style>
