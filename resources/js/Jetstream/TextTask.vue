<template>

            <div class="flex">
                <div class="px-6 xl:px-12 w-full max-w-3xl mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:w-3/4">
                    <div class="mt-0  overflow-hidden relative" v-for="contentBlock in textContent">
                        <div v-if="contentBlock.type === 'markdown'">
                            <VueShowdown :markdown="contentBlock.content"/>
                        </div>
                        <div v-else>
                            <quiz :quiz="contentBlock.content"></quiz>
                        </div>
                    </div>

                </div>
            </div>

</template>

<script>
    import JetApplicationLogo from './../Jetstream/ApplicationLogo'
    import Quiz from '../Jetstream/QuizTask'

    export default {
        components: {
            JetApplicationLogo,
            Quiz
        },
        props: {
            textContent: {
                type: Array,
                default: '',
            },
        },
        data() {
            return {

            }
        },
        methods: {
            computeAnswer(){

            },

        },
    }
</script>
