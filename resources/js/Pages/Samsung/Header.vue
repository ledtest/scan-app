<template>

    <form method="post" ref="scanForm">
        <input type="hidden" name="_token" v-model="token"/>
        <input type="hidden" name="_currentScanProductType" v-model="outs.productType"/>
        <input type="hidden" name="_lock" v-model="outs.unlockScanProductType"/>
        <div class="bg-white border-b-2 border-red-500 sticky shadow-lg md:shadow-none top-0 z-10">
            <div
                style="background: #c21500;background: -webkit-linear-gradient(to right, #c21500, #ffc500); background: linear-gradient(to right, #c21500, #ffc500);">
                <div class="container mx-auto md:px-20 text-center px-6 py-2 ">
                    <a href="#" class="text-gray-100 text-lg hover:text-gray-200">
                        <div v-if="outs.isNotBoxValid||outs.isGroupNotValid">
                            箱或盒号错误，箱号、盒号最少应是1
                        </div>
                        <div v-if="outs.scanType == ''">
                            型号条码必须有效且不能为空
                        </div>
                        <div v-if="outs.isScaned">
                            {{ outs.productType }}已经扫描过，可以<a
                            class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs"
                            href="#">补录盒扫描</a></div>
                        <div v-if="outs.isSaved">
                            {{ outs.productType }}扫描成功，型号已锁定，请在下方扫描盒条码，若要扫描新型号，点解锁
                        </div>
                    </a>
                </div>
            </div>
            <nav class="container mx-auto px-6 2xl:px-0 flex items-center justify-between flex-wrap bg-white py-2">
                <a href="#" class="flex items-center">
                    <div class=" h-full w-full lg:h-24 lg:w-24   lg:mb-0 mb-3 rounded-3xl"
                         style="background: /*cornflowerblue*/linear-gradient(to right, rgb(194, 21, 0), rgb(255, 197, 0));">
                        <div class=" w-full  object-scale-down lg:object-cover  lg:h-24 rounded-2xl flex">
                            <svg focusable="false" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 174.95 26.79">
                                <path style="fill:/*#1428a0*/whitesmoke"
                                      d="M638.8,443.72a3.11,3.11,0,0,1,0-1.31,2.06,2.06,0,0,1,2.29-1.69,2.14,2.14,0,0,1,2.32,2.27v1.55h6.24v-1.76c0-5.44-4.91-6.31-8.44-6.31-4.44,0-8.07,1.47-8.74,5.56a8.64,8.64,0,0,0,0,3.37c1.08,5.11,10,6.59,11.26,9.83a3.23,3.23,0,0,1,0,1.85,2.16,2.16,0,0,1-2.44,1.69,2.23,2.23,0,0,1-2.5-2.28v-2.41h-6.71V456c0,5.59,4.39,7.27,9.09,7.27,4.52,0,8.23-1.54,8.83-5.73a12.43,12.43,0,0,0,0-4.09C649,448.2,639.51,446.65,638.8,443.72Zm81.34.06a3.35,3.35,0,0,1,0-1.29,2.05,2.05,0,0,1,2.27-1.67,2.11,2.11,0,0,1,2.29,2.25v1.53h6.17v-1.74c0-5.39-4.83-6.24-8.33-6.24-4.41,0-8,1.45-8.66,5.51a8.35,8.35,0,0,0,.06,3.33c1.07,5.06,9.87,6.52,11.15,9.72a3.38,3.38,0,0,1,0,1.83c-.19.83-.75,1.67-2.41,1.67a2.21,2.21,0,0,1-2.49-2.24V454h-6.64v1.91c0,5.53,4.33,7.2,9,7.2,4.47,0,8.16-1.53,8.75-5.66a12.19,12.19,0,0,0,0-4.05C730.23,448.24,720.85,446.68,720.14,443.78Zm56.79,13.63L771,437.27h-9.28V462h6.14l-.36-20.78L773.86,462h8.9V437.27h-6.18Zm-118-20.14-4.63,25h6.76l3.49-23.15,3.41,23.15h6.71l-4.61-25Zm37.78,0-3.16,19.55-3.15-19.55h-10.2l-.54,25h6.25l.17-23.15,4.3,23.15h6.34l4.3-23.15.17,23.15h6.27l-.56-25Zm58.12,0H748.5v18.5a4.4,4.4,0,0,1-.06,1,2.49,2.49,0,0,1-4.79,0,4,4,0,0,1-.06-1v-18.5h-6.32V455.2c0,.46,0,1.41.06,1.65.44,4.67,4.12,6.19,8.72,6.19s8.29-1.52,8.73-6.19a13,13,0,0,0,.05-1.65Zm43.43,11v3.65h2.56v3.62a4.57,4.57,0,0,1-.07,1,2.7,2.7,0,0,1-5.11,0,6.21,6.21,0,0,1-.07-1V444.08a5.11,5.11,0,0,1,.11-1.18,2.62,2.62,0,0,1,5,0,6.59,6.59,0,0,1,.08,1v1.39H807v-.82a15,15,0,0,0,0-1.66c-.47-4.69-4.34-6.18-8.77-6.18s-8.23,1.5-8.78,6.18c0,.43-.13,1.2-.13,1.66v10.51a14.25,14.25,0,0,0,.1,1.65c.41,4.56,4.37,6.18,8.79,6.18s8.38-1.62,8.79-6.18c.07-.84.08-1.19.09-1.65v-6.7Z"
                                      transform="translate(-632.15 -436.48)"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                <div class="flex items-center md:hidden">
                    <div class="px-3 py-4 lg:py-3">
                        <a target="_blank" href="/tailwind/try"
                           class="flex items-center bg-green-400 hover:bg-green-500 hover:text-gray-100 text-white font-medium py-2 px-4 md:px-8 rounded-full font-hairline">
                            <span class="hidden md:inline">打印</span>
                            <span class="material-icons inline md:hidden">解锁</span>
                        </a>
                    </div>
                    <div>
                        <button @click="show=!show"
                                class="flex items-center px-3 py-2 border rounded text-gray-700 border-gray-200">
                            <svg class="fill-current h-4 w-4 font-bold" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <title>Menu</title>
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex-auto ml-3 justify-evenly py-2">
                    <div class="flex flex-wrap ">
                        <!--                    <div class="w-full flex-none text-xs text-blue-700 font-medium uppercase">
                                                samsung@{{ productType }}
                                            </div>-->
                        <h2 class="flex-auto text-lg font-lg">
                            扫码出库
                        </h2>
                        <div class="mx-3 w-90">
                            <input name="box" v-model="box"
                                   type="number"
                                   min="1"
                                   class="mx-1 w-20 font-semibold rounded-md px-2 py-1 leading-10 bg-white border-none border-purple text-purple hover:border-none hover:bg-purple hover:text-black"
                                   placeholder="箱"
                                   autoComplete="off"
                            />

                            <input name="group" v-model="group"
                                   type="number"
                                   min="1"
                                   class="mx-1 w-20 font-semibold rounded-md px-2 py-1 leading-10 bg-white border-none border-purple text-purple hover:border-none hover:bg-purple hover:text-black"
                                   placeholder="盒"
                                   autoComplete="off"
                            />

                        </div>
                    </div>
                    <p class="mt-3"></p>
                    <div class="flex py-4  text-sm text-gray-600">
                        <div class="flex-1 inline-flex items-center">
                            <p class="">
                                <template v-if="outs.scanType == 2">
                                    <input name="productType" v-model="productType"
                                           style="width: 26rem"
                                           class="border border-2 rounded-r px-4 py-2 w-60"
                                           type="text"
                                           placeholder="扫型号条码"
                                           disabled
                                           autoComplete="off"
                                    />
                                </template>
                                <template v-else>
                                    <input name="productType" v-model="productType"
                                           style="width: 26rem"
                                           class="border border-2 rounded-r px-4 py-2 w-60"
                                           type="text"
                                           placeholder="扫型号条码"
                                           autofocus
                                           autoComplete="off"
                                    />
                                </template>
                            </p>
                        </div>
                        <div class="flex-1 inline-flex items-center">
                            <p class="">
                                <template v-if="outs.scanType == 2">
                                    <input name="scanCode"
                                           v-model="scanCode"
                                           style="width: 28rem"
                                           class="scanCode border border-2 rounded-r px-4 py-2 w-60"
                                           type="text"
                                           autofocus
                                           autocomplete="off"
                                           placeholder="扫盒条码，盒号自动切换，箱号需扫描切换下一箱条码"/>
                                </template>
                                <template v-else>
                                    <input name="scanCode"
                                           v-model="scanCode"
                                           disabled
                                           style="width: 28rem"
                                           class="scanCode border border-2 rounded-r px-4 py-2 w-60"
                                           type="text"
                                           placeholder="扫盒条码，盒号自动切换，扫描下一箱条码切换箱号"/>
                                </template>
                            </p>
                        </div>
                    </div>
                    <div class="flex py-4  text-sm text-gray-600">
                        <div class="flex-1 inline-flex items-center">
                            <p class="">
                                <input name="productOriginPlace" v-model="productOriginPlace"
                                       style="width: 26rem"
                                       class="border border-2 rounded-r px-4 py-2 w-60"
                                       type="text"
                                       placeholder="请输入产地"
                                       autofocus
                                       autoComplete="off"
                                />
                            </p>
                        </div>
                        <div class="flex-1 inline-flex items-center">
                            <p class="">
                                <input name="productPO"
                                       v-model="productPO"
                                       style="width: 28rem"
                                       class="scanCode border border-2 rounded-r px-4 py-2 w-60"
                                       type="text"
                                       autocomplete="off"
                                       placeholder="请输入PO号"/>
                            </p>
                        </div>
                    </div>
                    <div class="flex p-4 pb-2 border-t border-gray-200 "></div>
                    <div class="flex space-x-3 text-sm font-medium">
                        <div class="flex-auto flex space-x-3">

                        </div>


                        <!--                    <button type="button"
                                                    @click.prevent="unlockScanType"
                                                    class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                                                    title="解锁后可扫描新型号，箱号不变"
                                            >解锁
                                            </button>
                                            <button type="button"
                                                    class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                                            >导出</button>
                                            <select class="rounded-md">
                                                <option>打印模板1</option>
                                                <option>打印模板2</option>
                                            </select>
                                            <template v-if="outs.scanedGroup">
                                                <button type="button"
                                                        @click.prevent="sendPrint"
                                                        class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                                                >打印
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button type="button"
                                                        @click.prevent="scanFirst"
                                                        class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                                                >打印
                                                </button>
                                            </template>

                                            <button type="button"
                                                    class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                                            >删单</button>-->

                    </div>
                </div>

                <div
                    class="border-t md:border-t-0 w-full flex-grow md:flex items-center md:justify-end md:w-auto hidden">
                    <div>
                        <a href="#"
                           class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                        >解锁</a>
                        <a href="#"
                           class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                        >导出</a>
                        <a href="#"
                           class="mx-1 px-3 py-1.5 font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-lg"
                        >删单</a>
                    </div>
                    <select class="bg-gray-100 rounded-md">
                        <option>打印模板1</option>
                        <option>打印模板2</option>
                    </select>
                    <div class="hidden md:block pl-3 py-6 md:py-3 ">
                        <button type="button"
                           @click.prevent="sendPrint"
                           class="bg-green-400 hover:bg-green-500 hover:text-gray-100 text-white font-medium py-2 px-8 rounded-full font-hairline"
                           style="background: linear-gradient(to right, rgb(194, 21, 0), rgb(255, 197, 0));">
                            打印
                        </button>
                    </div>
                </div>
            </nav>
        </div>
    </form>

</template>

<script>
import {createStore} from 'vuex'
import createPersistedState from "vuex-persistedstate"
import Button from "../../Jetstream/Button";

const store = createStore({
    state() {
        return {
            count: 0,
            productType: '',
            scanCode: '',
        }
    },
    mutations: {
        increment(state) {
            state.count++
        },
        initScanCode(state) {
            state.scanCode = 'test'
        },
        lockScanProductType(state, value) {
            state.productType = value
        },
    },
    plugins: [createPersistedState()],
})

export default {
    name: "Header",
    components: {Button},
    props: {
        outs: {
            type: Object
        }
    },
    data() {
        return {
            selectedGroup: [],
            productType: this.outs.productType,
            unlockScanProductType: this.outs.unlockScanProductType,
            scanCode: this.outs.scanCode,
            box: this.outs.box,
            group: this.outs.group,
            token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    watch: {
        productType: function (newQuestion, oldQuestion) {
            this.answer = '扫描保存中...'
            this.debouncedGetAnswer()
        },
        scanCode: function (newQuestion, oldQuestion) {
            this.scanAnswer = '扫描保存中...'
            this.debouncedGetScanCode()
        }
    },
    created: function () {
        this.debouncedGetAnswer = _.debounce(this.getAnswer, 500)
        this.debouncedGetScanCode = _.debounce(this.getScanCode, 500)

    },
    computed: {
        /*        count () {
                    return store.state.count
                },
                productType () {
                    return store.state.productType
                }*/
    },
    methods: {
        getAnswer: function () {
            if (this.productType.trim() === '') {
                window.alert('型号条码不能为空');
                return;
            }
            if (this.productType.indexOf('?') === -1) {
                this.answer = '请扫描'
                store.commit('lockScanProductType', this.productType)
                this.$refs.scanForm.submit();
                return;
            }
        },
        getScanCode: function () {
            if (this.scanCode.indexOf('?') === -1) {
                this.scanAnswer = '请扫描'
                let dc = this.scanCode.toString().substring(15, 19);
                let week = dc.toString().substring(2, 4);
                if (typeof week === "string" && parseInt(week) <= 53) {
                    this.$refs.scanForm.submit();
                } else {
                    window.alert('当前DC = ' + dc + '，不正确或周数大于53，请检查！');
                }
            }
        },
        unlockScanType: function () {
            this.$refs.scanForm[2].value = 'UNLOCK';
            this.$refs.scanForm.submit();
        },
        deleteGroup: function (index, data) {
            data._method = 'delete';
            this.$props.outs.scanedGroup.splice(index, 1)
            this.$props.outs.groupScanCode = data.code
            this.$props.outs.group = data.group
            this.$props.outs.groupScanSavedStatus = -1

            this.$refs.scanForm[8].focus()

            this.$inertia.post('/scan/samsung/form', data)
                .then((res) => {
                    console.log(res);
                });
        },
        scanFirst: function () {
            store.commit('initScanCode')
            window.alert('扫码后才能打印！')
        },
        sendPrint: function () {
            console.log(this.outs.productType)
            console.log(this.selectedGroup)
            this.$inertia.post('/scan/samsung/print', {
                'productType': this.outs.productType,
                'selected': this.selectedGroup
            })
                .then((res) => {
                    window.alert('打印标签生成完毕')
                    console.log(res)
                })

        },
        forcePrint: function (data) {
            this.$inertia.post('/scan/samsung/forcePrint', {
                'productType': this.outs.productType,
                'selected': this.selectedGroup,
                'row': data
            })
                .then((res) => {
                    window.alert('补打标签生成完毕')
                    console.log(res)
                })
        }
    }
}
</script>

<style scoped>

</style>
