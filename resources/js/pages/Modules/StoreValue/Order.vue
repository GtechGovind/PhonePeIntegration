<template>

    <nav-bar/>
    <hero/>

    <div class="bg-white m-2 p-3 shadow border text-center">
        <p class="font-bold text-lg text-gray-600">BUY NEW STORE VALUE PASS</p>
    </div>

    <div class="bg-white m-2 p-5 shadow border rounded">
        <div class="grid grid-cols-5 text-center content-center w-full items-center">
            <div v-on:click="addAmount(-100)"><i class="fas fa-minus-circle fa-xl mt-1"></i></div>
            <div class="mb-3 col-span-3">
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Enter Amount</label>
                <input
                    type="number"
                    id="price"
                    class="form_number_input"
                    placeholder="₹ 500"
                    required
                    v-model="pass.price"
                    v-on:keyup="validate"
                />
                <div class="c-error" v-if="error">
                    {{ error }}
                </div>
            </div>
            <div
                 v-on:click="addAmount(100)">
                <i class="fas fa-plus-circle fa-xl mt-1"></i>
            </div>
        </div>


        <div class="mt-3 grid grid-cols-3 gap-5">
            <chip :title="'₹ 100'" v-on:click="addAmount(100)"/>
            <chip :title="'₹ 200'" v-on:click="addAmount(200)"/>
            <chip :title="'₹ 500'" v-on:click="addAmount(500)"/>
        </div>

    </div>

    <Button
        v-on:click="genOrder"
        :is-loading="isLoading"
        :is-disabled="isDisabled"
        :type="'button'"
        :title="'PROCEED TO PAY ₹ ' + pass.price"
    />

</template>

<script>

import NavBar from "../../../Shared/NavBar";
import Hero from "../../../Shared/Hero";
import Button from "../../../Shared/Component/Button";
import Chip from "../../../Shared/Component/Chip";
import axios from "axios";

export default {

    name: "Order",
    components: {
        Chip,
        Button,
        Hero,
        NavBar
    },

    data() {
        return {
            pass: {
                price: 0
            },
            isLoading: false,
            isDisabled: false,
            error: null
        }
    },

    methods: {

        addAmount: function (amount) {
            this.pass.price += parseInt(amount)
            this.validate()
        },

        validate: function () {

            if (this.pass.price === '') {
                this.pass.price = 0
            } else if (this.pass.price < 100) {
                this.error = 'Amount must be grater then 100'
                this.isDisabled = true
            } else if (this.pass.price % 100 !== 0) {
                this.error = 'Amount must be multiple of 100'
                this.isDisabled = true
            } else if (this.pass.price > 3000) {
                this.error = 'Amount must not be grater then 3000'
                this.isDisabled = true
            } else {
                this.error = null
                this.isDisabled = false
                return true
            }
            return false
        },

        genOrder: async function () {
            this.isLoading = true
            this.isDisabled = true
            const response = await axios.post('/sv/create', this.pass)
            let data = await response.data
            if (data.status) this.onSuccess(data)
            else this.onFailure(data)
        },

        onSuccess: function (data) {
            this.isLoading = false
            const {redirectUrl} = data
            window.location.replace(redirectUrl)
        },

        onFailure: function (data) {
            this.isLoading = false
            const {errors} = data
            this.$swal.fire({
                icon: 'error',
                title: errors,
                text: errors,
            })
        },

    },


    async mounted() {

        const res = await axios.get('/sv/canIssuePass')
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

