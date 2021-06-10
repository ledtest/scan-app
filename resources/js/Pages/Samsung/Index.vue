<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                扫描型号
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!--                    <welcome />-->
                    <samsung />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Out from '@/Scan/Out'
import Samsung from '@/Pages/Samsung/Samsung';

export default {
    components: {
        AppLayout,
        Welcome,
        Samsung
    },
}
</script>
