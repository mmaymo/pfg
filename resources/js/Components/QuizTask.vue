<template>
    <section class="container bg-gray-200 ">
        <div class="p-4 text-center font-bold font-xl">
            <p>{{quiz.question}}</p>
        </div>
        <form id="quizForm" @submit="getAnswer">
            <div class="p-4 bg-white grid grid-cols-4 shadow">
                <p class="col-span-3">Elige la respuesta correcta</p>
                <jet-button type="submit" id="answerButton">Enviar Respuesta</jet-button>
            </div>
            <h2 class="uppercase my-2 p-4">Tu Respuesta</h2>
            <hr>
            <ul class="p-4">
                <li class="mt-2" v-for="(response, answerIndex) in quiz.responses">
                    <input type="radio" :id="answerIndex" :value="answerIndex" v-model="picked" ref="answer">
                    <label :for="answerIndex">{{response}}</label>
                </li>
            </ul>
        </form>
        <div id="answerMessage" class="p-4 m-4 rounded border-2 border-current" v-if="message">{{message}}</div>
    </section>
</template>

<script>
    import JetButton from "../Jetstream/Button";

    export default {
        components: {
            JetButton,
        },
        props: ['quiz', 'courseId', 'task'],
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
                if(this.correctAnswer === this.picked){
                    let messageDiv = this.$refs.answer[0].parentNode.parentNode
                    messageDiv.insertAdjacentHTML("afterend",'<p class="border-2 border-green-500">Esta es la respuesta correcta</p>')
                }
                if(this.correctAnswer !== this.picked){
                    let messageDiv = this.$refs.answer[0].parentNode.parentNode
                    messageDiv.insertAdjacentHTML("afterend",'<p class="border-2 border-red-500">Esta respuesta no es correcta, la respuesta correcta está recuadrada en verde</p>')
                }
                let greenAnswer = this.$refs.answer[this.correctAnswer].parentNode;
                greenAnswer.classList.add("border-2");
                greenAnswer.classList.add("border-green-500");

                let userAnswer = this.$refs.answer[this.picked].parentNode;
                userAnswer.classList.add("border-2");
                userAnswer.classList.add("border-red-500");
            },
            getAnswer(e) {
                e.preventDefault();
                let currentObj = this;
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                axios.post(route('solveTaskMultiple', {'course':this.courseId, 'task':this.task.id}), {
                    userAnswer:[this.picked]
                }).then(response => {
                    console.log(response.data)
                    currentObj.correctAnswer = response.data.index[0];
                    currentObj.message = response.data.message;
                    currentObj.isSubmitted = true;
                    currentObj.task.isDone = true;
                    currentObj.computeAnswer();
                }).catch(function (error) {
                    currentObj.correctAnswer = error;
                });
            }
        },
    }
</script>
