<template>
    <nav-bar />
    <hero />

    <div class="bg-white m-2 p-3 shadow border text-center">
        <p class="font-bold text-lg text-gray-600">RELOAD TRIP PASS</p>
    </div>

    <div class="container mx-auto rounded-lg">

        <div class="m-2 bg-white rounded border p-3">

            <!--SOURCE-->
            <div class="mb-6">
                <label class="c-label">Source</label>
                <input disabled class="c-input" type="text" :value="source">
            </div>

            <!--DESTINATION-->
            <div class="mb-6">
                <label class="c-label">Destination</label>
                <input disabled class="c-input" type="text" :value="destination">
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
                    <div class="font-semibold text-xl">₹ {{ fare }}</div>
                </div>
            </div>

            <Button
                :is-loading="isLoading"
                :is-disabled="isLoading"
                :type="'button'"
                :title="'PROCEED TO PAY ₹ ' + reload.reloadAmount"
                v-on:click="genOrder"
            />

        </div>


    </div>

</template>

<script>


import NavBar from "../../../Shared/NavBar";
import Hero from "../../../Shared/Hero";
import Button from "../../../Shared/Component/Button";
import axios from "axios";

export default {
    components: {Button, Hero, NavBar},
    props: {
        order_id: String,
        fare: Number,
        source: String,
        destination: String,
        trips: String,
        validity: String
    },

    data() {
        return {
            reload: {
                reloadAmount: this.fare,
                order_id: this.order_id
            },
            isLoading: false
        }
    },

    name: "Reload",

    methods: {
        genOrder: async function () {
            this.isLoading = true
            const response = await axios.post('/tp/reload', this.reload)
            let data = await response.data
            if (data.status) this.onSuccess(data)
            else this.onFailure(data)
        },

        onSuccess: function (data) {
            this.isLoading = false
            const { redirectUrl } = data
            window.location.href = redirectUrl
        },

        onFailure: function (data) {
            this.isLoading = false
            const { errors } = data
            this.errors = errors
        }
    }
}
</script>

<style scoped>

</style>
