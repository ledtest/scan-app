<template>

<!--    <a
        class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
        href="/storage/samsung_label.pdf">打印</a>-->


        <embed src="/storage/samsung_label.pdf" class="w-full" height="2100px"/>



</template>

<script>
import Button from "../Jetstream/Button";
export default {
    name: "SamsungPrint",
    components: {Button}
}
</script>

<style scoped>

</style>
