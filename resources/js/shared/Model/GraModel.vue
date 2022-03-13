<template>
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                Unable to exit ?
            </h3>
            <button type="button" v-on:click="close" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="need-help">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                Please selection station to continue ...
            </p>

            <div class="my-6">
                <label class="c-label">Select Station</label>
                <select class="c-select" v-model="station_id" v-on:change="getGraInfo">
                    <option disabled value="">Select station</option>
                    <option
                        v-for="{id, stn_id, stn_name} in stations"
                        :key="id"
                        :value="stn_id">
                        {{ stn_name }}
                    </option>
                </select>
            </div>

            <div class="bg-gray-200 rounded border p-3" v-if="gra.penalty">

                <div v-if="gra.penaltyNames || gra.penaltyAmount">
                    <div class="grid grid-cols-2">
                        <div class="text-gray-900 font-bold">
                            Penalties
                        </div>
                        <div>
                            {{ gra.penaltyNames }}
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="text-gray-900 font-bold">
                            Penalty Amount
                        </div>
                        <div>
                            ₹ {{ gra.penaltyAmount }}
                        </div>
                    </div>
                </div>


                <div v-else>
                    <div class="bg-gray-200 rounded border p-3 text-gray-900 font-bold text-center">
                        No Penalties found !
                    </div>
                </div>

            </div>

        </div>

        <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">

            <Button
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
</template>

<script>
import Button from "../Component/Button";
import axios from "axios";

export default {
    name: "GraModel",
    components: {Button},
    props: {
        slave_id: String,
        stations: Array
    },
    data() {
        return {
            status: Boolean,
            error: String,
            isGraButtonLoading: false,
            station_id: '',
            gra: {
                penalty: null,
                penaltyNames: null,
                penaltyAmount: null,
            }
        }
    },
    methods: {

        close: function () {
            this.showRefund = false
            this.showGra = false
            this.showHelp = true
            toggleModal('need-help', false)
        },

        getGra: function () {
            this.showGra = true
            this.showRefund = false
            this.showHelp = false
        },

        getGraInfo: async function () {

            const res = await axios.get('/gra/' + this.slave_id + "/" + this.station_id )
            const data = await res.data;

            if (data.status) {

                this.gra.penalty = data.data
                const {penalties, overTravelCharges} = data.data

                if (penalties.length && overTravelCharges.length)
                {
                    this.gra.penaltyNames = 'Penalty + Over Travel'

                    penalties.forEach((penalty) => {
                        this.gra.penaltyAmount += penalty.amount
                    })
                    overTravelCharges.forEach((overTravelCharge) => {
                        this.gra.penaltyAmount += overTravelCharge.amount
                    })
                }
                else if (penalties.length)
                {
                    this.gra.penaltyNames = 'Penalty'

                    penalties.forEach((penalty) => {
                        this.gra.penaltyAmount += penalty.amount
                    })
                }
                else if (overTravelCharges.length)
                {
                    this.gra.penaltyNames = 'Over Travel'

                    overTravelCharges.forEach((overTravelCharge) => {
                        this.gra.penaltyAmount += overTravelCharge.amount
                    })
                }
                else
                {
                    this.gra.penaltyNames = null
                    this.gra.penaltyAmount = null
                }
            }
            else {
                this.close()
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.error,
                })
            }
        },

        applyGra: async function () {
            this.isGraButtonLoading = true
            const res = await axios.post('/gra', {
                penaltyInfo: this.gra.penalty,
                station_id: this.station_id
            })
            const {error, redirectUrl, status} = await res.data
            if (status) {
                this.isRefundButtonLoading = false
                this.close()
                window.location.href = redirectUrl
            } else {
                this.isRefundButtonLoading = false
                this.close()
                this.$swal.fire({
                    icon: 'error',
                    title: error,
                    text: error,
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
