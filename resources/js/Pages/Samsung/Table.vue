<template>

    <div class="my-2 ml-4 mr-4 py-2">
        <button @click="$emit('enlarge-text', 0.1)">
            发送
        </button>
        <table class="min-w-max w-full table-auto">
            <thead>
<!--            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal" style="background: rgb(255, 197, 0);color: saddlebrown;">-->
<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-1 px-6 text-center">#</th>
                <th class="py-3 px-1 text-center">选择<input class="ml-1" type="checkbox"/></th>
                <th class="py-3 px-6 text-center">箱</th>
                <th class="py-3 px-6 text-center">盒</th>
                <th class="py-3 px-6 text-center">P/N</th>
                <th class="py-3 px-6 text-center">LOT</th>
                <th class="py-3 px-6 text-center">QTY</th>
                <th class="py-3 px-6 text-center">DC</th>
                <th class="py-3 px-6 text-center">产地</th>
                <th class="py-3 px-6 text-center">PO号</th>
                <th class="py-3 px-6 text-center">扫描时间</th>
                <th class="py-3 px-6 text-center">操作</th>
            </tr>

            </thead>
            <tbody class="text-gray-600 text-sm font-light">

            <template v-if="outs.scanType">

                <template v-for="(group, index) in outs.scanedGroup">

                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <div class="flex items-center">

                                <span class="font-medium">{{ index + 1 }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <div class="flex items-center">

                                <input type="checkbox" name="selectedGroup" :value="group.id"
                                       v-model="selectedGroup"/>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center">
                                {{ group.box }}
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            {{ group.group }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                {{ group.pn }}
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                {{ group.code.toString().substring(0, 10) }}
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                {{ group.code.toString().substring(12, 15) }}
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                {{ group.code.toString().substring(15, 19) }}
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center">

                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center">

                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                {{ group.created_at }}
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <button type="button"
                                    class="mx-1 px-2 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                                    @click.prevent="forcePrint(group)"
                            >补打
                            </button>
                            <button type="button"
                                    @click.prevent="deleteGroup(index, group)"
                                    class="mx-1 px-2 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                            >删除
                            </button>
                        </td>
                    </tr>

                </template>

            </template>

            <template v-else>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">1</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <div class="flex items-center">
                            <input type="checkbox"/>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex items-center justify-center">
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">

                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <button type="button"
                                class="mx-1 px-2 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                        >补打
                        </button>
                        <button type="button"
                                class="mx-1 px-2 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                        >删除
                        </button>
                    </td>
                </tr>
            </template>

            </tbody>
        </table>

    </div>

</template>

<script>
export default {
    name: "Table",
    props: {
        outs: {
            type: Object
        }
    },
    data() {
        return {
            selectedGroup: [],
        }
    },
    setup(props, context) {
        const colorValue = ({
            color: props.types || "#1989fa",
            submits: () => {
                context.emit("submitFn");
            },
        });
        return {
            colorValue,
        };
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
