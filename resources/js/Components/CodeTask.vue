<template>
    <div>
        <div v-if="textContent.content" class="p-4 text-justify">
            <VueShowdown :markdown="textContent.content"/>
        </div>
        <jet-button class="col-start-2 col-span-2" @click.native="testCode">Muestra respuesta</jet-button>
        <div>{{ testResult }}</div>
    </div>
</template>

<script>
import JetButton from "../Jetstream/Button";


    export default {
        components: {
            JetButton,
        },
        props: ['textContent', 'courseId', 'taskId'],
        data() {
            return {
                testResult: ""
            }
        },
        methods: {
            testCode() {
                let currentObj = this;
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                axios.post(route('testCodeTask', {'course':this.courseId, 'task':this.taskId}), {
                    userAnswer:"touch '/var/log/test.log'"
                }).then(response => {
                    console.log(response)
                    currentObj.testResult = response.data

                }).catch(function (error) {
                    console.log(error)
                });
            },

        },
    }
</script>
