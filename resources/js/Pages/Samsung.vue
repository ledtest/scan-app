<template>
    <div>
        <div className="p-6 sm:px-20 bg-white border-b border-gray-200">
            <!--            <div>
                            <jet-application-logo className="block h-12 w-auto"/>
                        </div>-->

            <!--            <div id="vueRoot">
                            <form v-on:submit.prevent="submit" method="post" ref="form">
                                <input type="hidden" name="_token" v-model="form.token"/>
                                <input style="white-space: pre-line;" type="text" autofocus name="box"
                                       v-model="form.box" v-on:keydown.enter.prevent="addCategory"
                                       v-on:keyup.space="inputSubmit"
                                       @input.stop="scanSubmit"
                                />
                                                    <a href="#" v-on:click="submit">SUBMIT</a>

                            </form>
                        </div>-->

            <form method="post" ref="form">

                <input type="hidden" name="_token" v-model="form.token"/>

                <div className="mt-8 text-2xl">
                    <!--                    <button
                                            class="w-full sm:w-auto flex-none bg-gray-900 hover:bg-gray-700 text-white text-lg leading-6 font-semibold py-3 px-6 border border-transparent rounded-xl focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-900 focus:outline-none transition-colors duration-200 ml-4">
                                            扫描下一箱
                                        </button>-->
                    正在扫描 Samsung 第<strong class="p-2">
                    <!--                {{ formInput.box }}-->
                    <input name="box" v-model="form.box" placeholder="箱号"
                           class="w-20 font-semibold rounded-md px-2 py-1 leading-10 bg-white border-none border-purple text-purple hover:border-none hover:bg-purple hover:text-black"
                           type="number" min="1"/>
                </strong>箱， 第<strong class="p-2">
                    <!--                {{ formInput.group }}-->
                    <input name="group" v-model="form.group" placeholder="盒号"
                           class="w-20 font-semibold rounded-md px-2 py-1 leading-10 bg-white border-none border-purple text-purple hover:border-none hover:bg-purple hover:text-black"
                           type="number" min="1"/>
                </strong>盒
                    <!--                <input v-model="formInput.box" placeholder="箱号" @mouseleave="check"
                                           class="font-semibold rounded-md px-4 py-1 leading-10 bg-white border border-purple text-purple hover:bg-purple hover:text-black"
                                           type="number" min="1"/>-->

                    <!--                    <button
                                            class="w-full sm:w-auto flex-none bg-gray-900 hover:bg-gray-700 text-white text-lg leading-6 font-semibold py-3 px-6 border border-transparent rounded-xl focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-900 focus:outline-none transition-colors duration-200 ml-4">
                                            扫描下一组
                                        </button>-->

                    <div class="float-right">

                        <input name="question" v-model="form.question" class="ml-4" style="width: 300px" autofocus type="text" placeholder="先扫型号，再扫盒号"/>
                        <button
                            class="ml-4 w-full sm:w-auto flex-none bg-gray-900 hover:bg-gray-700 text-white text-lg leading-6 font-semibold py-3 px-6 border border-transparent rounded-xl focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-900 focus:outline-none transition-colors duration-200">
                            解锁
                        </button>

                        <button
                            @click="submitPrint"
                            class="w-full sm:w-auto flex-none bg-gray-900 hover:bg-gray-700 text-white text-lg leading-6 font-semibold py-3 px-6 border border-transparent rounded-xl focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-900 focus:outline-none transition-colors duration-200 ml-4"
                        >
                            打印
                        </button>

                    </div>

                </div>

                <div className="mt-6 text-gray-400">
                    <input id="scanArea" name="scanCode" type="text"
                           disabled
                           autocomplete="off"
                           ref="input"
                           spellcheck="false"
                           v-model="form.scanCode"
                           v-on:keydown.enter.prevent="addCategory"
                           v-on:keyup.space="inputSubmit"
                           @input.stop="scanSubmit"
                           placeholder="条码扫描，盒号系统自动切换，箱号需扫描切换下一箱条码"
                           class="rq-form-element h-20 w-full font-semibold rounded-md px-4 py-1 leading-10 bg-white border border-purple text-purple hover:bg-purple hover:text-black ml-4"/>

                    <div class="px-4 py-2">
                        <!--                        <label for="autoSave">
                                                    <input id="autoSave" name="autoSave" type="checkbox" class="ml-6"/>
                                                    <small class="ml-2">勾选此项，扫描后将自动保存，不勾选扫描后需手动按下Enter键保存</small>
                                                </label>-->
                        <small class="ml-4">编号：{{ form.savedBox }} / {{ form.savedGroup }}，条码：{{ form.savedScanCode }}
                            {{ parseInt(form.savedSaveMessage) === 0 ? '未保存' : '已保存' }}</small>
                    </div>
                </div>
            </form>

        </div>

        <div className="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-1">
            <div className="p-6">


                <div>

                    <table class="table-fixed w-full">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>箱号</td>
                            <td>选择</td>
                            <td>P/N</td>
                            <th>LOT</th>
                            <th>QTY</th>
                            <th>DC</th>
                            <th>扫描时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <input type="checkbox"/>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>
</template>

<style scoped>
#scanArea:focus {
    background: azure;
}
</style>

<script>
import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
import Input from "../Jetstream/Input";
import Button from "../Jetstream/Button";
import Label from "../Jetstream/Label";

import Type from '@/Pages/Samsung/Type';

import Form from "./Samsung/Form";

export default {
    components: {
        Label,
        Button,
        Input,
        JetApplicationLogo,
        Type,
        Form,
    },
    props: {
        outs: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                box: this.outs.box,
                savedBox: this.outs.savedBox,
                group: this.outs.group,
                savedGroup: this.outs.savedGroup,
                scanCode: this.outs.scanCode,
                savedScanCode: this.outs.savedScanCode,
                saveMessage: this.outs.saveMessage,
                savedSaveMessage: this.outs.savedSaveMessage,
                token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                question: '',
                answer: 'I cannot give you an answer until you ask a question!',
            })
        }
    },
    created: function () {
        this.debouncedGetAnswer = _.debounce(this.getAnswer, 500)
    },
    watch: {
        question: function (newQuestion, oldQuestion) {
            this.form.answer = 'Waiting for you to stop typing...'
            this.debouncedGetAnswer()
        }
    },
    methods: {
        getAnswer: function () {
            console.log(100);
            if (this.form.question.indexOf('?') === -1) {
                this.form.answer = 'Questions usually contain a question mark. ;-)'
                console.log(this.form.question);
                this.$refs.form.submit();
                return
            }
            this.form.answer = 'Thinking...'
            var vm = this
            this.$inertia.get('http://api.wpbom.com/api/neran.php')
                .then(function (response) {
                    console.log(response);
                    vm.answer = _.capitalize(response.data.answer)
                })
                .catch(function (error) {
                    console.error(error);
                    vm.answer = 'Error! Could not reach the API. ' + error
                })
        },
        inputSubmit: function (e) {
            if (e) e.preventDefault();
            console.log('scan submit');
            // this.submit(e);
        },
        addCategory: function (e) {
            if (e) e.preventDefault();
            console.log("添加成功");
        },
        submit: function (e) {
            e.preventDefault();
            console.log(e);
            this.$refs.form.submit();
        },
        // 非中文
        scanSubmit: function (e) {
            const that = e.target; // 获取dom
            /*
            // 正则匹配：大小字母加数字-每四个字符加空格-删除最后一位空格（用于展示）
            this.form.box = that.value.replace(/[^0-9A-Za-z]/g, '').replace(/(.{4})/g, '$1 ').replace(/ $/g, '');
            const data = this.form.box.replace(/ /g, ''); // 匹配去掉所有空格（用于提交）
             */
            console.log(that.value);
            this.form.box = that.value.replace(/ /g, '') + '#';
            // setTimeout(this.$refs.form.submit(e), 3000);
        },
    }
}
</script>
