<!--
<template>
    <nav-bar/>
    <hero/>

    <div class="bg-white m-2 p-3 shadow border text-center">
        <p class="font-bold text-lg text-gray-600">RELOAD TRIP PASS</p>
    </div>

    <div class="container mx-auto rounded-lg">

        <form @submit.prevent="reload.post('/tp/reload')">

            <div class="m-2 bg-white rounded border p-3">

                &lt;!&ndash;SOURCE&ndash;&gt;
                <div class="mb-6">
                    <label class="c-label">Source</label>
                    <input disabled class="c-input" type="text" :value="source">
                </div>

                &lt;!&ndash;DESTINATION&ndash;&gt;
                <div class="mb-6">
                    <label class="c-label">Destination</label>
                    <input disabled class="c-input" type="text" :value="destination">
                </div>

                &lt;!&ndash;TYPE AND QUANTITY&ndash;&gt;
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
                <Button :title="'PROCEED TO PAY ₹ ' + reload.reloadAmount"/>
            </div>



        </form>

    </div>

</template>

<script>
import NavBar from "../../Shared/NavBar";
import Hero from "../../Shared/Hero";
import {useForm} from "@inertiajs/inertia-vue3";
import Button from "../../Shared/Components/Button";

export default {
    components: {Button, Hero, NavBar,},
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
            reload: useForm({
                reloadAmount: this.fare,
                order_id: this.order_id
            }),
        }
    },

    name: "Reload"
}
</script>

<style scoped>

</style>
-->
