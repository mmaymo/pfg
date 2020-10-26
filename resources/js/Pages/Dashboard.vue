<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hola
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <text-task></text-task>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../Layouts/AppLayout'
    import Quiz from '../Jetstream/QuizTask'
    import TextTask from '../Jetstream/TextTask'


    export default {
        components: {
            AppLayout,
            Quiz,
            TextTask
        },
    }
</script>
