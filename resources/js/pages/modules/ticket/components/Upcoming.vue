<template>
    <div>
        <div class="my-3">
          <span class="m-2 px-2 py-1 border rounded-full bg-gray-200 text-base text-gray-700 font-medium">
            <span class="flex-initial max-w-full leading-none text-xs font-normal">Upcoming Ticket</span>
        </span>
        </div>
        <div v-for="ticket in upcomingTickets" :key="ticket.sale_or_id" class="bg-white rounded shadow hover:bg-gray-50 m-2 ">
            <Link :href="'/ticket/view/' + ticket.sale_or_no">
                <div class="grid grid-cols-5">
                    <div class="grid grid-rows-3 col-span-4 p-3">
                        <div class="text-xl text-gray-600 font-semibold">
                            {{ ticket.source }}
                            <i v-if="ticket.product_id === 1" class="fa-solid fa-right-long mx-2"></i>
                            <i v-if="ticket.product_id === 2" class="fa-solid fa-arrow-right-arrow-left mx-2"></i>
                            {{ ticket.destination }}
                        </div>
                        <div class="text-sm content-center grid grid-cols-5 mt-2 text-gray-600">
                            <div>₹ {{ ticket.total_price }}</div>
                            <div class="text-center col-span-3">
                                {{ ticket.product_id === 1 ? "Single Journey" : "Return Journey" }}
                            </div>
                            <div class="text-center">{{ ticket.unit }} <i class="fa-solid fa-user mx-2"></i></div>
                        </div>
                        <div class="text-sm content-center text-gray-600 mt-2">
                            <div>Valid till {{ ticket.ms_qr_exp }}</div>
                        </div>
                    </div>
                    <div class="grid grid-rows-2 bg-blue-400">
                        <div class="row-span-3 text-center">
                            <div class="grid content-center h-full">
                                <i class="fa-solid fa-angle-right fa-2xl text-gray-50 hover:text-blue-600"></i>
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
        upcomingTickets: Object
    },
    name: "Upcoming",
    components: {
        Link
    }
}
</script>

<style scoped>

</style>
