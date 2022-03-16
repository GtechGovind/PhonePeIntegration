<template>
    <div class="h-screen grid grid-rows-3">
        <div class="mx-auto flex items-center row-span-2">
            <div class="text-center">
                <p class="text-gray-600 font-bold">Generating your ticket please wait ...</p>
                <Spinner />
            </div>
        </div>
        <div class="mx-auto flex items-center">
            <img :src="coBrand" class="h-24" alt="logo">
        </div>
    </div>
</template>

<script>

import Spinner from "../../../Shared/Component/Spinner";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";

import axios from "axios";

export default {

    name: "Processing",

    components: {Spinner},

    props: {
        order: String
    },

    data() {
        return {
            coBrand: '/img/atek_logo.png'
        }
    },

    async mounted() {
        console.log(usePage().props)
        await this.initCreation()
    },

    methods: {

        initCreation: async function () {
            const res = await axios.get("/processing/init/" + this.order)
            const data = res.data
            if (data.status) this.onSuccess(data)
            else this.onFailure(data)
        },

        onSuccess: function (data) {

            const {product_id, op_type_id, order_id} = data

            if (op_type_id === 1)
            {
                if (product_id === 1 || product_id === 2) this.$inertia.get('/ticket/view/' + this.order)
                else if (product_id === 3) this.$inertia.get('/sv/dashboard')
                else this.$inertia.get('/tp/dashboard')
            }
            else if (op_type_id === 3)
            {
                if (product_id === 3) this.$inertia.get('/sv/dashboard')
                else this.$inertia.get('/tp/dashboard')
            }
            else
            {
                if (product_id === 1 || product_id === 2) this.$inertia.get('/ticket/view/' + order_id)
                else if (product_id === 3) this.$inertia.get('/sv/dashboard')
                else this.$inertia.get('/tp/dashboard')
            }

        },

        onFailure: function (data) {
            this.$swal.fire({
                icon: 'error',
                title: 'Payment Failed !',
                text: data.error,
                confirmButtonText: 'Try Again',
                showDenyButton: true,
                denyButtonText: 'Go Home !'
            }).then((res) => {
                if (res.isConfirmed) {
                    this.$inertia.get("/processing/" + this.order)
                } else if (res.isDenied) {
                    this.$inertia.get('/products')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
