<template>
    <div>
        <div class="my-3">
            <span class="m-2 px-2 py-1 border rounded-full bg-gray-200 text-base text-gray-700 font-medium">
                <span class="flex-initial max-w-full leading-none text-xs font-normal">Frequent Ticket</span>
            </span>
        </div>
        <div v-for="ticket in recentTickets" class="bg-white rounded shadow hover:bg-gray-50 m-2 ">
            <Link :href="'/ticket/order/' + ticket.src_stn_id + '/' + ticket.des_stn_id">
                <div class="grid grid-cols-5">
                    <div class="grid grid-rows-2 col-span-4 p-3">
                        <div class="text-xl text-gray-600 font-semibold">
                            {{ ticket.source }} <i class="fa-solid fa-right-long mx-2"></i> {{ ticket.destination }}
                        </div>
                        <div class="text-sm content-center h-full mt-2">
                            Click to buy again, fare ₹ {{ ticket.total_price }} / ticket
                        </div>
                    </div>
                    <div class="grid grid-rows-2 bg-blue-400">
                        <div class="row-span-2 text-center">
                            <div class="grid content-center h-full">
                                <i class="fa-solid fa-angle-right fa-2xl text-gray-50 hover:text-blue-700"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
    </div>
</template>

<script>

import {Link} from '@inertiajs/inertia-vue3'

export default {
    props: {
        recentTickets: Object
    },
    name: "RecentTicket",
    components: {
        Link
    }
}
</script>

<style scoped>

</style>
