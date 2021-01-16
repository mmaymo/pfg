<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <section class="container" >
                <form id="myForm" @submit="getAnswer">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid ">
                                <ul>
                                    <p>{{quiz.question}}</p>
                                    <li v-for="(response, answerIndex) in quiz.responses">
                                        <input type="radio" :id="answerIndex"  :value="answerIndex" v-model="picked" ref="answer">
                                        <label :for="answerIndex">{{response}}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <jet-button type="submit" id="answerButton">Enviar Respuesta</jet-button>
                </form>
                <div v-if="message">{{message}}</div>
            </section>
        </div>
    </div>
</template>

<script>
    import JetButton from "../Jetstream/Button";

    export default {
        components: {
            JetButton,
        },
        props: ['quiz', 'courseId', 'taskId'],
        data() {
            return {
                picked:null,
                isSubmitted:false,
                correctAnswer: null,
                message:null,
            }
        },
        methods: {
            computeAnswer(){
                let greenAnswer = this.$refs.answer[this.correctAnswer].parentNode;
                greenAnswer.classList.add("bg-green-500");

                let userAnswer = this.$refs.answer[this.picked].parentNode;
                userAnswer.classList.add("bg-red-500");
            },
            getAnswer(e) {
                e.preventDefault();
                let currentObj = this;
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                axios.post(route('solveTask', {'course':this.courseId, 'task':this.taskId}), {
                    userAnswer:this.picked
                }).then(response => {
                    currentObj.correctAnswer = response.data.index;
                    currentObj.message = response.data.message;
                    currentObj.isSubmitted = true;
                    currentObj.computeAnswer();
                }).catch(function (error) {
                    currentObj.correctAnswer = error;
                });
            },
        },
    }
</script>
