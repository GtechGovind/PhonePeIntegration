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
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    v-model="tripPass.source_id" v-on:change="getFare">
                    <option disabled value="">Select source station</option>
                    <option
                        v-for="{ id, stn_id, stn_name } in stations"
                        :key="id"
                        :value="stn_id">
                        {{ stn_name }}
                    </option>
                </select>
                <div class="block mb-2 text-sm text-red-500" v-if="errors.source_id">
                    {{ errors.source_id }}
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
                <div class="block mb-2 text-sm text-red-500" v-if="errors.destination_id">
                    {{ errors.destination_id }}
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

    <Button
        :is-loading="isLoading"
        :is-disabled="isDisabled"
        v-on:click="genOrder"
        type="'button'"
        :title="'PROCEED TO PAY ₹ ' + tripPass.fare"
    />

</template>

<script>

import axios from "axios";
import Button from "../../../Shared/Component/Button";
import NavBar from "../../../Shared/NavBar";
import Hero from "../../../Shared/Hero";

export default {

    props: {
        stations: Array,
        trips: Number,
        validity: Number,
    },

    name: "Order",

    components: {Hero, NavBar, Button},

    data() {
        return {
            tripPass: {
                source_id: '',
                destination_id: '',
                fare: 0,
                pass_id: "21"
            },
            isLoading: false,
            errors: {
                source_id: null,
                destination_id: null
            },
            isDisabled: true
        }
    },

    methods: {

        getFare: async function () {
            if (this.isValid) {
                const response = await axios.post('/api/get/fare', {
                    "source": this.tripPass.source_id,
                    "destination": this.tripPass.destination_id,
                    "pass_id": this.tripPass.pass_id
                });
                let data = await response.data
                if (data.status) {
                    this.tripPass.fare = data.fare
                    this.isDisabled = false
                }
                else console.log(data.errors)
            }
        },

        genOrder: async function () {
            if (this.isValid) {
                this.isLoading = true
                const response = await axios.post('/tp/create', this.tripPass)
                let data = await response.data
                if (data.status) this.onSuccess(data)
                else this.onFailure(data)
            }
        },

        onSuccess: function (data) {
            this.isLoading = false
            const {redirectUrl} = data
            window.location.replace(redirectUrl)
        },

        onFailure: function (data) {
            this.isLoading = false
            const {errors} = data
            this.errors = errors
        },

        isValid: function () {

            console.log("hi")

            if(this.tripPass.fare === 0) {
                this.isDisabled = true
                this.errors.source_id = 'Source & destination can\'t be same !'
                this.errors.destination_id = 'Source & destination can\'t be same !'

                return false

            } else {
                this.isDisabled = false
                return  true
            }


        }

    },


    async mounted() {

        const res = await axios.get('/tp/canIssuePass')
        const data = await res.data

        if (!data.status) {

            this.$swal.fire({
                icon: 'error',
                title: 'Active Pass already exists !',
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
