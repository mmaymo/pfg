<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

            <section class="container" >
                <form id="myForm" @submit.prevent="$emit('submitted')">

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

                    <button type="submit" id="answerButton" v-on:click="computeAnswer();">Enviar Respuesta</button>
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
        props: {
            quiz: {
                type: Object,
                default: '',
            },
        },
        data() {
            return {
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
                this.isSubmitted = false
                this.$refs.answer.map(function (element) {
                    return element.parentNode.classList.remove("bg-green-500","bg-red-500")
                })
            },

        },
    }
</script>
