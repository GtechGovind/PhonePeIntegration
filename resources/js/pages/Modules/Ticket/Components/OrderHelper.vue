<template>
    <div>
        <div class="container mx-auto">
            <div class="m-2 bg-white rounded border p-3">

                <!--SOURCE-->
                <div class="mb-6">
                    <label class="c-label">Source</label>
                    <select class="c-select" v-model="ticket.source_id" v-on:change="getFare">
                        <option disabled value="">Select source station</option>
                        <option
                            v-for="{id, stn_id, stn_name} in stations"
                            :key="id"
                            :value="stn_id">
                            {{ stn_name }}
                        </option>
                    </select>
                    <div class="c-error" v-if="errors.source_id">
                        {{ errors.source_id }}
                    </div>
                </div>

                <!--DESTINATION-->
                <div class="mb-6">
                    <label class="c-label">Destination</label>
                    <select class="c-select" v-model="ticket.destination_id" v-on:change="getFare">
                        <option disabled value="">Select source station</option>
                        <option
                            v-for="{id, stn_id, stn_name} in stations"
                            :key="id"
                            :value="stn_id">
                            {{ stn_name }}
                        </option>
                    </select>
                    <div class="c-error" v-if="errors.destination_id">
                        {{ errors.destination_id }}
                    </div>
                </div>

                <!--TYPE AND QUANTITY-->
                <div class="mb-6 grid grid-cols-2 gap-12 content-center w-full">
                    <div class="grid grid-cols-2 text-center content-center w-full">
                        <label class="mx-2 text-gray-900">
                            <input
                                type="radio"
                                name="type"
                                value="10"
                                v-model="ticket.pass_id"
                                v-on:change="getFare">
                            Single
                        </label>
                        <label class="mx-2 text-gray-900">
                            <input
                                type="radio"
                                name="type"
                                value="90"
                                v-model="ticket.pass_id"
                                v-on:change="getFare">
                            Return
                        </label>
                    </div>
                    <div class="grid grid-cols-5 text-center content-center w-full items-center">
                        <div class="col-span-2"
                             v-on:click="ticket.quantity > 1 ? ticket.quantity-- : ticket.quantity">
                            <i class="fas fa-minus-circle fa-xl mt-1"></i>
                        </div>
                        <p v-text="ticket.quantity" class="text-gray-600 text-3xl font-bold"
                           v-on:change="getFare">
                        </p>
                        <div class="col-span-2"
                             v-on:click="ticket.quantity < 6 ? ticket.quantity++ : ticket.quantity">
                            <i class="fas fa-plus-circle fa-xl mt-1"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="border rounded-lg border-dashed py-5 border-3 border-blue-700 bg-white m-2">

            <!--TOTAL AMOUNT-->
            <div class="text-center pb-1 border-b border-dashed">
                <h5 class="text-gray-400">You have to pay</h5>
                <span class="text-gray-600 text-3xl font-bold text-center">₹
                {{ ticket.quantity * ticket.fare }}
            </span>
            </div>

            <!--OTHER DETAILS-->
            <div class="px-4 mt-1 pb-1 border-b border-dashed">
                <div class="grid grid-cols-2">
                    <div class="text-gray-600">Journey Type</div>
                    <div class="text-right font-bold text-gray-600">
                        {{ ticket.pass_id === "10" ? "Single Journey" : "Return Journey" }}
                    </div>
                    <div class="text-gray-600">Passengers</div>
                    <div class="text-right font-bold text-gray-600">
                        {{ ticket.quantity }}
                        <i class="fa-solid fa-user mx-2"></i>
                    </div>
                </div>
            </div>

            <!--DETAILED FARE-->
            <div class="px-4 mt-1">
                <div class="grid grid-cols-2">
                    <div class="text-gray-600">Total Fare</div>
                    <div class="text-right font-bold text-gray-600">₹
                        {{ ticket.fare }} X {{ ticket.quantity }}
                        =
                        ₹ {{ ticket.quantity * ticket.fare }}
                    </div>
                </div>
            </div>
        </div>

        <Button
            :type="'button'"
            :title="'PROCEED TO PAY ₹ ' + ticket.quantity * ticket.fare"
            :is-loading="isLoading"
            :is-disabled="isDisabled"
            v-on:click="genOrder"
        />

    </div>

</template>

<script>
import axios from "axios";
import Button from "../../../../Shared/Component/Button";

export default {

    name: "OrderHelper",

    components: {Button},

    data() {
        return {
            ticket: {
                source_id: '',
                destination_id: '',
                quantity: 1,
                fare: 0,
                pass_id: "10"
            },
            errors: {
                source_id: null,
                destination_id: null
            },
            isLoading: false,
            isDisabled: false
        }
    },

    props: {
        stations: Array
    },

    methods: {

        getFare: async function () {
            this.isDisabled = true
            if (this.isValid()) {
                const response = await axios.post('/api/get/fare', {
                    "source": this.ticket.source_id,
                    "destination": this.ticket.destination_id,
                    "pass_id": this.ticket.pass_id
                });
                let data = await response.data
                if (data.status) {
                    this.ticket.fare = data.fare
                    this.isDisabled = false
                }
            }
        },

        genOrder: async function () {
            if (this.isValid()) {
                this.isLoading = true
                this.isDisabled = true
                const response = await axios.post('/ticket/create', this.ticket)
                let data = await response.data
                if (data.status) this.onSuccess(data)
                else this.onFailure(data)
            }
        },

        onSuccess: function (data) {
            this.isLoading = false
            this.isDisabled = false
            const {redirectUrl} = data
            window.location.href = redirectUrl
        },

        onFailure: function (data) {
            this.isLoading = false
            this.isDisabled = false
            const {errors, message} = data
            this.$swal.fire({
                icon: 'error',
                title: message,
                text: errors,
            })
        },

        isValid: function () {
            if (this.ticket.source_id === '') {
                this.isLoading = false
                this.isDisabled = true
                this.errors.source_id = 'Please select source station !'
            } else if (this.ticket.destination_id === '') {
                this.isLoading = false
                this.isDisabled = true
                this.errors.destination_id = 'Please select destination station !'
            } else if (this.ticket.source_id === this.ticket.destination_id) {
                this.isLoading = false
                this.isDisabled = true
                this.errors.source_id = 'Source & destination can\'t be same !'
                this.errors.destination_id = 'Source & destination can\'t be same !'
            } else {
                this.isDisabled = false
                this.errors.source_id = null
                this.errors.destination_id = null
                return true
            }
            return false
        }
    },
}
</script>

<style scoped>

</style>
