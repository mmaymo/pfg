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
                    <button type="submit" id="answerButton">Enviar Respuesta</button>
                </form>
            </section>
            <!--/container-->
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from './../Jetstream/ApplicationLogo'

    export default {
        components: {
            JetApplicationLogo,

        },
        props: ['quiz', 'courseId', 'taskId'],
        data() {
            return {
                picked:null,
                isSubmitted:false,
                correctAnswer: null,
                answer: this.$inertia.form({
                    index:this.picked,
                }, {
                    bag: 'answerQuiz',
                    resetOnSuccess: false,
                })
            }
        },
        methods: {
            computeAnswer(){

               /* let correctAnswer = this.$refs.answer[this.quiz.questions[this.questionIndex].correctAnswerIndex].parentNode
                correctAnswer.classList.add("bg-green-500");
                this.userResponses[this.questionIndex] = answer
                if(answer){
                    this.score++
                }else{
                    let userAnswer = this.$refs.answer[this.picked].parentNode
                    userAnswer.classList.add("bg-red-500");
                }*/

            },
            getAnswer(e) {
                e.preventDefault();
                let currentObj = this;
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                axios.post(route('solveTask', {'course':this.courseId, 'task':this.taskId}), {
                    index:this.picked
                }).then(response => {
                    currentObj.correctAnswer = response.data
                    currentObj.isSubmitted = true
                }).catch(function (error) {
                    currentObj.correctAnswer = error;
                });
            },
        },
    }
</script>
