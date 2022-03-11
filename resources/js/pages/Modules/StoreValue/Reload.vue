<template>

    <NavBar/>
    <hero/>

    <div class="bg-white m-2 p-3 shadow border text-center">
        <p class="font-bold text-lg text-gray-600">Reload StoreValue Pass</p>
    </div>

    <form @submit.prevent="reload.post('/sv/reload')">

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
                <div class="block m-1 text-sm text-red-500" v-if="reload.errors.reloadAmount">
                    {{ reload.errors.reloadAmount }}
                </div>
            </div>
            <div class="mt-3 grid grid-cols-3 gap-5">
                <chip :title="'₹ 100'" v-on:click="addAmount(100)"/>
                <chip :title="'₹ 200'" v-on:click="addAmount(200)"/>
                <chip :title="'₹ 500'" v-on:click="addAmount(500)"/>
            </div>
        </div>

        <Button :title="'PROCEED TO PAY ₹ ' + reload.reloadAmount" />

    </form>
</template>

<script>

import NavBar from "../../Shared/NavBar";
import {useForm} from '@inertiajs/inertia-vue3';
import Chip from "../../Shared/Chip";
import Hero from "../../Shared/Hero";
import Button from "../../Shared/Components/Button";

export default {

    props: {
        order_id: String
    },

    name: "Reload.vue",

    components: {Button, Hero, Chip, NavBar},

    data() {
        return {
            reload: useForm({
                reloadAmount: 0,
                order_id: this.order_id
            }),
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

        }
    }
}
</script>

<style scoped>

</style>
