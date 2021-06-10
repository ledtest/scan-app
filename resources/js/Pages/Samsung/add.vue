<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                扫描条码
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!--                    <welcome />-->
<!--                    <out />-->
                    <card />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Out from '@/Scan/Out'
import card from '@/Pages/Samsung/card'

export default {
    components: {
        AppLayout,
        Welcome,
        Out,
        card
    },
}
</script>

<style scoped>

</style>
