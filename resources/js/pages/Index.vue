<template>
    <div class="container mx-auo">
        <div class="h-screen grid grid-rows-3">
            <div class="mx-auto flex items-center row-span-2">
                <div class="text-center">
                    <img :src="logo" class="h-24" alt="logo">
                    <Spinner/>
                </div>
            </div>
            <div class="mx-auto flex items-center">
                <img :src="coBrand" class="h-24" alt="logo">
            </div>
        </div>
    </div>
</template>

<script>

import Spinner from "../Shared/Component/Spinner";
import axios from "axios";

export default {

    name: "Index",

    components: {Spinner},

    data() {
        return {
            logo: '/img/logo.png',
            coBrand: '/img/atek_logo.png'
        }
    },

    mounted() {
        this.initPhonePe()
    },

    methods: {

        /*
            INITIALIZING PHONE PE
        */
        initPhonePe: function () {
            PhonePe.PhonePe.build(PhonePe.Constants.Species.web).then((sdk) => {
                sdk.fetchAuthToken().then((res) => {
                    const newRes = JSON.parse(JSON.stringify(res))
                    const {grantToken: token} = newRes
                    this.authenticate(token)
                    console.info('Token generated successfully ..')
                }).catch((err) => {
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Unable to connect !',
                        text: err,
                        confirmButtonText: 'Try Again',
                    }).then((res) => {
                        if (res.isConfirmed) this.$inertia.get('/')
                    })
                })
            })
        },

        /*
            AUTHENTICATING USER FROM PHONEPE
        */
        authenticate: async function (token) {

            const res = await axios.post('/auth', {token: token})
            const data = await res.data
            const {status, error} = data

            if (status)
            {
                this.$inertia.replace('/products')
            }
            else
            {
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error,
                })
                this.$swal.fire({
                    icon: 'error',
                    title: 'Do you want to save the changes?',
                    showConfirmButton: true,
                    confirmButtonText: 'Try again !',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.initPhonePe()
                    }
                })
            }
        }

    }
}
</script>

<style scoped>

</style>
