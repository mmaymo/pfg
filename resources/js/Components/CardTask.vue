<template>
    <section class="container bg-gray-200 ">
        <div class="p-4 text-center font-bold font-xl">
            <p>{{textContent.card.front}}</p>
        </div>
        <div class="p-4 bg-white grid grid-cols-4 shadow">
            <jet-button class="col-start-2 col-span-2" @click.native="answer">Muestra respuesta</jet-button>
        </div>

        <div class="p-4" v-show="showAnswer">
            <h2 class="uppercase my-2">Una Respuesta Correcta</h2>
            <hr>
            <p class="p-2 text-justify">{{textContent.card.back}}</p>
        </div>
    </section>
</template>

<script>
import JetButton from "../Jetstream/Button";

export default {
    components: {
        JetButton,
    },
    props: {
        textContent: {
            type: Object,
            default: {},
        },
    },
    data() {
        return {
            showAnswer: false,
        }
    },
    methods: {
        answer() {
            this.showAnswer = !this.showAnswer
        },

    },
}
</script>
