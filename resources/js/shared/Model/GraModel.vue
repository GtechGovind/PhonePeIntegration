<template>
    <div class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-20 z-50 justify-center items-center h-modal" id="gra-help">
        <div class="relative px-4 w-full max-w-md h-auto">
            <div class="relative bg-white rounded-lg shadow">

                <!--HEADER-->
                <div class="flex justify-between items-start px-5 py-4 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">Unable to exit ?</h3>
                    <button type="button" v-on:click="close" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="gra-help">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!--BODY-->
                <div class="px-6 py-2">

                    <!--SELECT STATION-->
                    <div class="mb-3">
                        <label class="c-label">Select Station</label>
                        <select class="c-select" v-model="station_id" v-on:change="graInfo">
                            <option disabled value="">Select station</option>
                            <option
                                v-for="{id, stn_id, stn_name} in stations"
                                :key="id"
                                :value="stn_id">
                                {{ stn_name }}
                            </option>
                        </select>
                    </div>

                    <!--GRA INFO-->
                    <div class="bg-blue-200 p-3 rounded-lg" v-if="gra.penaltyNames || gra.penaltyAmount">
                        <h1 class="text-gray-700 font-bold mb-2">Penalty Info</h1>
                        <div class="h-full bg-white p-3 rounded-lg">
                            <div class="m-2">
                                <table class="w-full rounded-lg">
                                    <tbody>

                                    <tr class="bg-white border-b">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            Penalties
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                            {{ gra.penaltyNames }}
                                        </td>
                                    </tr>

                                    <tr class="bg-white border-b">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            Penalty Amount
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">₹
                                            {{ gra.penaltyAmount }}
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!--NO GRA-->
                    <div class="bg-blue-200 p-3 rounded-lg" v-if="!(gra.penaltyNames || isLoading)">
                        <h1 class="text-gray-700 font-bold mb-2">Penalty Info</h1>
                        <div class="h-full bg-white p-3 rounded-lg">
                            <div class="m-2 p-3 text-center">
                                <p class="text-gray-600 font-bold">No penalties found.</p>
                            </div>
                        </div>
                    </div>

                    <!--LOADING-->
                    <div class="bg-blue-200 p-3 rounded-lg" v-if="isLoading">
                        <h1 class="text-gray-700 font-bold mb-2">Penalty Info</h1>
                        <div class="h-full bg-white p-3 rounded-lg">
                            <div class="m-2 p-3 text-center">
                                <svg class="inline w-8 h-8 text-gray-200 animate-spin fill-blue-600"
                                     viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                        fill="currentColor"/>
                                    <path
                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                        fill="currentFill"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!--BUTTONS-->
                    <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                        <Button
                            v-if="gra.penaltyAmount > 0"
                            :is-loading="isGraButtonLoading"
                            :is-disabled="isGraButtonLoading"
                            :title="'Proceed to pay ₹' + gra.penaltyAmount"
                            :type="'button'"
                            v-on:click="applyGra"
                        />
                        <Button
                            :is-loading="false"
                            :is-disabled="false"
                            :title="'Decline'"
                            :type="'button'"
                            v-on:click="close"
                        />
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Button from "../Component/Button";
import axios from "axios";

export default {

    data() {
        return {
            isGraButtonLoading: false,
            station_id: '',
            isLoading: false,
            gra: {
                penalty: null,
                penaltyNames: null,
                penaltyAmount: null,
            }
        }
    },

    name: "GraModel",

    components: {Button},

    props: {
        slave_id: String,
        stations: Array
    },

    methods: {

        graInfo: async function () {

            this.isLoading = true

            const res = await axios.get('/gra/' + this.slave_id + "/" + this.station_id)
            const data = await res.data;

            if (data.status) {

                this.isLoading = false

                const {penalties, overTravelCharges} = data.data
                this.gra = {penalty: null, penaltyNames: null, penaltyAmount: null}
                this.gra.penalty = data.data

                if (penalties.length && overTravelCharges.length) {
                    this.gra.penaltyNames = 'Penalty + Over Travel'

                    penalties.forEach((penalty) => {
                        this.gra.penaltyAmount += penalty.amount
                    })

                    overTravelCharges.forEach((overTravelCharge) => {
                        this.gra.penaltyAmount += overTravelCharge.amount
                    })

                } else if (penalties.length) {
                    this.gra.penaltyNames = 'Penalty'

                    penalties.forEach((penalty) => {
                        this.gra.penaltyAmount += penalty.amount
                    })

                } else if (overTravelCharges.length) {
                    this.gra.penaltyNames = 'Over Travel'

                    overTravelCharges.forEach((overTravelCharge) => {
                        this.gra.penaltyAmount += overTravelCharge.amount
                    })

                }
            } else {
                this.isLoading = false
                this.close()
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.error,
                })
            }

        },

        applyGra: async function() {

            this.isGraButtonLoading = true

            const res = await axios.post('/gra', {
                penaltyInfo: this.gra.penalty,
                station_id: this.station_id
            })

            const {error, status, redirectUrl} = await res.data

            if (status) {
                this.isGraButtonLoading = false
                window.location.href = redirectUrl
                this.close()
            } else {
                this.isGraButtonLoading = false
                this.close()
                this.$swal.fire({
                    icon: 'error',
                    title: error,
                    text: error,
                })
            }
        },

        close: function () {
            this.gra.penalty = null
            this.gra.penaltyNames = null
            this.gra.penaltyAmount = null
            toggleModal('gra-help', false)
        },

    }
}

</script>
