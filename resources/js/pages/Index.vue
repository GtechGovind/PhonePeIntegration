<template>
    <div class="relative rounded-lg m-2 bg-white">
        <div class="grid w-full content-center p-7" >
            <img :src="logo" alt="..." class="h-24 mx-auto">
            <Spinner />
        </div>
    </div>
</template>

<script>

import Spinner from "../Shared/Component/Spinner";

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
        authenticate: function (token) {
            this.$inertia.post('auth', {token: token})
        }

    }
}
</script>

<style scoped>

</style>
