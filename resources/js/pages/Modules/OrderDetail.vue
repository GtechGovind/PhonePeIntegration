<template>
    <div class="m-4">
        <div class="h-screen bg-gray-50 border rounded shadow-lg">

            <div class="bg-blue-400 h-24 rounded">
                <div class="grid justify-items-center items-center h-full">
                    <div>
                        <h3 class="text-white font-bold text-xl">ORDER DETAILS</h3>
                    </div>
                </div>
            </div>

            <div class="m-2">
                <table class="w-full rounded-lg">
                    <tbody>

                    <tr class="bg-white border-b">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            ORDER ID
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ order.order_id }}
                        </td>
                    </tr>

                    <tr class="bg-white border-b" v-if="order.ms_qr_no">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            MASTER ID
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ order.ms_qr_no }}
                        </td>
                    </tr>

                    <tr class="bg-white border-b" v-if="order.pg_txn_no">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            PAYMENT ID
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ order.pg_txn_no }}
                        </td>
                    </tr>

                    <tr class="bg-white border-b">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            PRODUCT
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            <template v-if="order.product_id == 1">
                                Single Journey
                            </template>
                            <template v-if="order.product_id == 2">
                                Return Journey
                            </template>
                            <template v-if="order.product_id == 3">
                                Store Value Pass
                            </template>
                            <template v-if="order.product_id == 4">
                                Trip Pass
                            </template>
                        </td>
                    </tr>

                    <tr class="bg-white border-b">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            AMOUNT
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ order.total_price }}
                        </td>
                    </tr>

                    <tr class="bg-white border-b" v-if="order.source_id">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            SOURCE
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ order.source_id }}
                        </td>
                    </tr>

                    <tr class="bg-white border-b" v-if="order.destination_id">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            DESTINATION
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ order.destination_id }}
                        </td>
                    </tr>

                    <tr class="bg-white border-b">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                            ORDER DATE
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ order.txn_date }}
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    order: Object
})

</script>

<style scoped>

</style>
