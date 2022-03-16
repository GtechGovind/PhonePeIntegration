<template>
    <nav-bar />
    <div class="bg-white border shadow m-2 p-3">
        <h3 class="font-bold text-center my-3 text-gray-800">Terms & Condition</h3>
        <ol class="text-sm text-gray-700 text-justify">
            <li class="mb-3">1. Single Journey QR Ticket (SJT) will be valid for journey from selected “Origin” & “Destination” Station. Passenger can also exit from station between “Origin” & “Destination” station.</li>
            <li class="mb-3">2. If Customer exit from station which is prior to “Destination” station with lower fare bracket, customer will not able to seek any refund for lesser travel.</li>
            <li class="mb-3">3. Return Journey QR Ticket (RJT) will be valid for journey from selected “Origin” & “Destination” Station. Passenger can also exit from station between “Origin” & “Destination” station. Two QR Tickets one from “Origin” and “Destination” (Outward Journey) and another from “Destination” and “Origin” (Return Journey) will be issued.</li>
            <li class="mb-3">4. For RJT, on return, If Customer exit from station which is prior to “Origin” station (currently “Destination”) with lower fare bracket, customer will not able to seek any refund for lesser travel.</li>
            <li class="mb-3">5. Family/Friend travel: customer from one mobile can place maximum 2 Orders with up to 6 SJT/RJT tickets with same “Origin” and “Destination” for each order. Individual QR Tickets will be issued for each journey.</li>
            <li class="mb-3">6. Customer purchasing multiple tickets will be allowed entries with different QR tickets one by one and followed by one by one exits equal to entries made.</li>
            <li class="mb-3">7. For over travel beyond “Destination” station, exit will be allowed after paying the fare difference and new QR will be issued for exit.</li>
            <li class="mb-3">8. For over time beyond time limits as per Metro rules, exit will be allowed after paying the penalty amount and new QR will be issued for exit.</li>
            <li class="mb-3">9. Both SJT and RJT are valid up to the last train of same day only.</li>
            <li class="mb-3">10. In case of Cancellation/Refund, amount will be credited back to original source of payment after deducting the refund charges as per Metro rules.</li>
        </ol>
    </div>
</template>

<script>
import NavBar from "../../Shared/NavBar";
export default {
    name: "Terms",
    components: {NavBar}
}
</script>

<style scoped>

</style>
