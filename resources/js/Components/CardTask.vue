<template>
    <div class="flex">
        <div class="px-6 xl:px-12 w-full max-w-3xl mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:w-3/4">
            <div class="mt-0  overflow-hidden relative">
                <h2>{{textContent.card.front}}</h2>
                <button @click="answer">Muestra respuesta</button>
            </div>
            <div v-show="showAnswer" class="mt-0  overflow-hidden relative">
                <h2>{{textContent.card.back}}</h2>
            </div>

        </div>
    </div>
</template>

<script>

export default {
    components: {
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
