<template>
    <form method="post" ref="form">
        <input type="hidden" name="_token" v-model="token"/>
        <div class="ml-4 py-2" id="watch-example">
            <p>
                Ask a yes/no question:
                <input name="question" type="text" v-model="question" autofocus autocomplete="off"/>
            </p>
            <p>{{ answer }}</p>
        </div>
    </form>
</template>

<script>

export default {
    data() {
        return {
            question: '',
            answer: 'I cannot give you an answer until you ask a question!',
            token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    watch: {
        // whenever question changes, this function will run
        question: function (newQuestion, oldQuestion) {
            this.answer = 'Waiting for you to stop typing...'
            this.debouncedGetAnswer()
        }
    },
    created: function () {
        // _.debounce is a function provided by lodash to limit how
        // often a particularly expensive operation can be run.
        // In this case, we want to limit how often we access
        // yesno.wtf/api, waiting until the user has completely
        // finished typing before making the ajax request. To learn
        // more about the _.debounce function (and its cousin
        // _.throttle), visit: https://lodash.com/docs#debounce
        this.debouncedGetAnswer = _.debounce(this.getAnswer, 500)
    },
    methods: {
        getAnswer: function () {
            if (this.question.indexOf('?') === -1) {
                this.answer = 'Questions usually contain a question mark. ;-)'
                console.log(this.question);
                this.$refs.form.submit();
                return
            }
            this.answer = 'Thinking...'
            var vm = this
            // axios.get('/Api/api/neran.php')
            this.$inertia.get('http://api.wpbom.com/api/neran.php')
                .then(function (response) {
                    console.log(response);
                    vm.answer = _.capitalize(response.data.answer)
                })
                .catch(function (error) {
                    console.error(error);
                    vm.answer = 'Error! Could not reach the API. ' + error
                })
        }
    }
}
</script>

<style scoped>

</style>
