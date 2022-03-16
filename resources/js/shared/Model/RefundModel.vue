<template>
    <div class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-20 z-50 justify-center items-center md:inset-0 h-modal sm:h-full" id="refund-help">
        <div class="relative px-4 w-full max-w-md h-full md:h-auto">

            <!--REFUND INFO-->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                        Refund Ticket
                    </h3>
                    <button type="button" v-on:click="close" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="refund-help">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                        Refunding ticket will refund all of the tickets, Please verify below details !
                    </p>
                    <table class="min-w-full border rounded-lg bg-gray-50">
                        <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Ticket Fare
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                ₹ {{ refund.pass_price }}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Processing Charges ({{ refund.processing_fee }}%)
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                ₹ {{ refund.processing_fee_amount}}
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Refundable Amount
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                ₹ {{ refund.refund_amount }}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">

                    <Button
                        :is-loading="isRefundButtonLoading"
                        :is-disabled="isRefundButtonLoading"
                        :title="'Yes, Refund Order'"
                        :type="'button'"
                        v-on:click="refundTicket"
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
</template>

<script>
import Button from "../Component/Button";
import axios from "axios";

export default {
    components: {Button},
    name: "RefundModel",
    props: {
        order_id: String,
    },
    data() {
        return {
            status: Boolean,
            error: String,
            isRefundButtonLoading: false,
            refund: {
                order_id: null,
                processing_fee: null,
                processing_fee_amount: null,
                refund_amount: null,
                pass_price: null,
            },
        }
    },
    methods: {

        close: function () {
            this.showRefund = false
            this.showGra = false
            this.showHelp = true
            toggleModal('refund-help', false)
        },

        getRefundInfo: async function () {
            this.isRefundButtonLoading = true
            const res = await axios.get('/refund/' + this.order_id)
            const data = await res.data
            if (data.status) {
                this.showHelp = false
                this.showRefund = true
                this.refund = data.refund
                this.isRefundButtonLoading = false
            } else {
                this.isRefundButtonLoading = false
                this.close()
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.error,
                })
            }
        },

        refundTicket: async function () {
            this.isRefundButtonLoading = true
            const res = await axios.get('/refund/ticket/' + this.order_id)
            const data = await res.data
            if (data.status) {
                this.isRefundButtonLoading = false
                this.close()
                this.$swal.fire(
                    '₹' + this.refund.refund_amount +
                    ' Refunded Successfully !',
                )
                window.location.href = '/products'
                history.pushState(null, document.title, location.href);
            } else {
                this.isRefundButtonLoading = false
                this.close()
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.error,
                })
            }
        },
    },
    mounted() {
        this.getRefundInfo()
    }
}
</script>

<style scoped>

</style>
