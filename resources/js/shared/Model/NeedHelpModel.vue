<template>
    <div
        class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-20 z-50 justify-center items-center md:inset-0 h-modal sm:h-full" id="need-help">
        <div class="relative px-4 w-full max-w-md h-full md:h-auto">

            <!--NEED HELP-->
            <div v-if="showHelp" class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex justify-end p-2">
                    <button v-on:click="close" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="need-help">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 pt-0 text-center">
                    <svg class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Please select the type of
                        help, you need ?
                    </h3>

                    <Button
                        :is-loading="isRefundButtonLoading"
                        :is-disabled="isRefundButtonLoading"
                        :title="'Refund Order'"
                        :type="'button'"
                        v-on:click="getRefundInfo"
                    />

                    <Button
                        :is-loading="isGraButtonLoading"
                        :is-disabled="isGraButtonLoading"
                        :title="'Unable to exit ?'"
                        :type="'button'"
                        v-on:click="getRefundInfo"
                    />

                </div>
            </div>

            <!--REFUND INFO-->
            <div v-if="showRefund" class="relative bg-white rounded-lg shadow dark:bg-gray-700 border rounded-lg bg-gray-50">
                <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                        Refund Ticket
                    </h3>
                    <button type="button" v-on:click="close" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="need-help">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                        Refunding ticket will refund all of the tickets, Please verify below details !
                    </p>
                    <table class="min-w-full">
                        <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Ticket Fare
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ refund.pass_price }}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Processing Charges
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ refund.processing_fee }}
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Refundable Amount
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ refund.refund_amount }}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div
                    class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                    <button v-on:click="refundTicket" data-modal-toggle="need-help" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        I accept
                    </button>
                    <button v-on:click="close" data-modal-toggle="need-help" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                        Decline
                    </button>
                </div>
            </div>

            <!--GRA INFO-->


        </div>
    </div>
</template>

<script>
import axios from "axios";
import Button from "../Component/Button";

export default {
    components: {Button},
    props: {
        order_id: String,
        slave_id: String,
    },
    name: "NeedHelpModel",
    data() {
        return {
            status: Boolean,
            error: String,
            showHelp: true,
            showRefund: false,
            showGra: false,
            isRefundButtonLoading: false,
            isGraButtonLoading: false,
            refund: {
                order_id: null,
                processing_fee: null,
                processing_fee_amount: null,
                refund_amount: null,
                pass_price: null,
            },
            gra: Object
        }
    },
    methods: {
        close: function () {
            this.showRefund = false
            this.showGra = false
            this.showHelp = true
            toggleModal('need-help', false)
        },
        getRefundInfo: async function () {
            this.isButtonLoading = true
            const res = await axios.get('/refund/' + this.order_id)
            const data = await res.data
            if (data.status) {
                this.showHelp = false
                this.showRefund = true
                this.refund = data.refund
                this.isRefundButtonLoading = false
            } else {
                this.isRefundButtonLoading = false
            }
        },
        refundTicket: function () {

        }
    }
}
</script>

<style scoped>

</style>
