<template>

    <nav-bar/>
    <hero/>

    <div class="bg-white m-2 p-3 shadow border text-center">
        <p class="font-bold text-lg text-gray-600">Reload StoreValue Pass</p>
    </div>

    <div class="bg-white m-2 p-5 shadow border rounded">
        <div class="mb-3">
            <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Enter Amount</label>
            <input
                type="number"
                id="price"
                class="form_number_input"
                placeholder="₹ 500" required v-model="reload.reloadAmount"
                v-on:keyup="validate"
            />
            <div class="block m-1 text-sm text-red-500" v-if="errors">
                {{ reload.errors.reloadAmount }}
            </div>
        </div>
        <div class="mt-3 grid grid-cols-3 gap-5">
            <chip :title="'₹ 100'" v-on:click="addAmount(100)"/>
            <chip :title="'₹ 200'" v-on:click="addAmount(200)"/>
            <chip :title="'₹ 500'" v-on:click="addAmount(500)"/>
        </div>
    </div>

    <Button
        :is-loading="isLoading"
        :is-disabled="isLoading"
        :type="'button'"
        :title="'PROCEED TO PAY ₹ ' + reload.reloadAmount"
        v-on:click="genOrder"
    />

</template>

<script>


import Chip from "../../../Shared/Component/Chip";
import Hero from "../../../Shared/Hero";
import NavBar from "../../../Shared/NavBar";
import Button from "../../../Shared/Component/Button";
import axios from "axios";
export default {

    props: {
        order_id: String
    },

    name: "Reload.vue",

    components: {Button, NavBar, Hero, Chip},

    data() {
        return {
            reload: {
                reloadAmount: 0,
                order_id: this.order_id
            },
            isLoading: false,
            errors: null
        }
    },

    methods: {

        addAmount: function (amount) {
            this.reload.reloadAmount += parseInt(amount)
        },

        validate: function () {

            if (this.pass.price < 100) {
                this.pass.errors.price = 'Amount must be grater then 100'
            } else if (this.pass.price % 100 !== 0) {
                this.pass.errors.price = 'Amount must be multiple of 100'
            } else if (this.pass.price > 3000) {
                this.pass.errors.price = 'Amount must not be grater then 3000'
            } else {
                this.pass.errors.price = ''
            }

        },

        genOrder: async function () {
            this.isLoading = true
            const response = await axios.post('/sv/reload', this.reload)
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
