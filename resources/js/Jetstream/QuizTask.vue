<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="mt-8 text-2xl">
                {{this.quiz.chapter}}
            </div>
            <div>
                <progress id="progress" :value="this.questionIndex" :max="this.quiz.questions.length"> </progress>
            </div>
            <!--container-->
            <section class="container" >
                <form id="myForm" @submit.prevent="$emit('submitted')">
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <ul>
                                    <p>{{quiz.questions[questionIndex].text}}</p>
                                    <li v-for="(response, answerIndex) in quiz.questions[questionIndex].responses">
                                        <label :for="answerIndex">{{response.text}}</label>
                                        <input type="radio" :id="answerIndex"  :value="answerIndex" v-model="picked" ref="answer">
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="answerButton" v-on:click="computeAnswer();">Enviar Respuesta</button>
                    <button v-if="(this.quiz.questions.length > questionIndex+1) && isSubmitted" type="button" id="next" v-on:click="next();">Siguiente</button>
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
        data() {
            return {
                quiz: {
                    user: "Dave",
                    chapter: "nombre del capítulo",
                    questions: [
                        {
                            text: "What is the full form of HTTP?",
                            responses: [
                                {text: "Hyper text transfer package"},
                                {text: "Hyper text transfer protocol", correct: true},
                                {text: "Hyphenation text test program"},
                                {text: "None of the above"}
                            ],
                            correctAnswerIndex: 1
                        },
                        {
                            text: "HTML document start and end with which tag pairs?",
                            responses: [
                                {text: "HTML", correct: true},
                                {text: "WEB"},
                                {text: "HEAD"},
                                {text: "BODY"}
                            ],
                            correctAnswerIndex: 0
                        },
                        {
                            text: "Which tag is used to create body text in HTML?",
                            responses: [
                                {text: "HEAD"},
                                {text: "BODY", correct: true},
                                {text: "TITLE"},
                                {text: "TEXT"}
                            ],
                            correctAnswerIndex: 1
                        }
                    ]
                },

                questionIndex: 0,
                userResponses: [],
                score:0,
                isActive: false,
                picked:null,
                isSubmitted:false
            }
        },
        methods: {
            computeAnswer(){
                let answer = this.quiz.questions[this.questionIndex].responses[this.picked].correct?true:false
                let correctAnswer = this.$refs.answer[this.quiz.questions[this.questionIndex].correctAnswerIndex].parentNode
                correctAnswer.classList.add("bg-green-500");
                this.userResponses[this.questionIndex] = answer
                if(answer){
                    this.score++
                }else{
                    let userAnswer = this.$refs.answer[this.picked].parentNode
                    userAnswer.classList.add("bg-red-500");
                }
                this.isSubmitted = true

                //esto luego lo tengo que guardar en db para recuperarlo userResponses, score del quiz
                //cuando se carga, si ya estaba hecho se tienen que mostrar las respuestas originales
                //y entonces tengo que deshabilitar el poder reenviar o aumentar el número de intentos
                //necesito un botón para resetearlo

            },
            next(){
                this.questionIndex++
                this.isSubmitted = false
                this.$refs.answer.map(function (element) {
                    return element.parentNode.classList.remove("bg-green-500","bg-red-500")
                })
            },

        },
    }
</script>
