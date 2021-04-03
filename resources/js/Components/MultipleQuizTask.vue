<template>
    <section class="container bg-gray-200 ">
        <div class="p-4 text-center font-bold font-xl">
            <p>{{quiz.question}}</p>
        </div>
        <form id="quizForm" @submit="getAnswer">
            <div class="p-4 bg-white grid grid-cols-4 shadow">
                <p class="col-span-3">Elige la respuesta correcta, pueden ser más de una</p>
                <jet-button type="submit" id="answerButton">Enviar Respuesta</jet-button>
            </div>
            <h2 class="uppercase my-2 p-4">Tu Respuesta</h2>
            <hr>
            <div class="p-4 bg-white grid grid-cols-4 shadow">

                <ul class="p-4 col-span-4">
                    <li class="mt-2" v-for="(response, answerIndex) in quiz.responses">
                        <input type="checkbox" :id="answerIndex" :value="answerIndex" v-model="picked" ref="answer">
                        <label :for="answerIndex">{{response}}</label>
                    </li>
                </ul>
            </div>
        </form>
        <div class="p-4 m-4 rounded border-2 border-current" v-if="message">{{message}}</div>
    </section>
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
                picked:[],
                isSubmitted:false,
                correctAnswer: [],
                message:null,
                multiple: "true",
            }
        },
        methods: {
            computeAnswer(){
                if(this.correctAnswer.length >0){
                    let greenAnswer
                    this.correctAnswer.forEach(e => {
                        greenAnswer = this.$refs.answer[e].parentNode;
                        greenAnswer.classList.add("bg-green-500");
                    })
                    let userAnswer
                    this.picked.forEach(e => {
                        userAnswer = this.$refs.answer[e].parentNode;
                        userAnswer.classList.add("bg-red-500");
                    })
                }

            },
            getAnswer(e) {
                e.preventDefault();
                let currentObj = this;
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                axios.post(route('solveTaskMultiple', {'course':this.courseId, 'task':this.taskId}), {
                    userAnswer:this.picked
                }).then(response => {
                    console.log(response.data)
                    currentObj.correctAnswer = response.data.index
                    currentObj.message = response.data.message;
                    currentObj.isSubmitted = true;
                    currentObj.computeAnswer();
                    //añadir task.isDone true
                    currentObj.taskCompleted.isDone = true
                }).catch(function (error) {
                    currentObj.correctAnswer = error;
                });
            },
        },
    }
</script>
