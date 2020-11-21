<template>
    <div id="mainContentWrap"
         class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
        <div id="mainContent">
            <div id="task" class="flex">
                <div class="pb-4 w-full pt-8 lg:pt-8">
                    <div
                        class="max-w-3xl mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:px-12 xl:w-3/4 ">
                        <div class="flex items-center">
                            <h1 class="text-xl font-bold capitalize">
                                {{ this.itinerary[0].chapter }}</h1>
                        </div>
                        <h2 class="group flex whitespace-pre-wrap relative capitalize">
                            {{ this.itinerary[0].name }}</h2>
                    </div>
                    <text-task :textContent="this.itinerary[0].textContent"></text-task>
                    <div id="bottomTaskButtons"
                         class="flex border-b border-gray-300 p-8 pt-16">
                        <div class="w-2/4">
                            <button
                                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                Atrás
                            </button>
                            <p>Task name anterior</p>
                        </div>
                        <div class="w-2/4 text-right">
                            <button
                                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                Siguiente
                            </button>
                            <p>Task name siguiente</p>
                        </div>

                    </div>
                    <div id="footer" class="pt-8">
                        <div class="text-right w-full pr-8"><a :href="this.mailLink" target="_blank" rel="noopener noreferrer">Informa de un problema en la tarea</a></div>
                        <div class="text-right w-full pr-8"><a href="http://portal.uned.es/" target="_blank">Dudas</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>



    import Quiz from "./QuizTask";
    import TextTask from "./TextTask";

    export default {
        components: {
            Quiz,
            TextTask,

        },
        props: {
            course: {
                type: String,
                default: '',
            },
            teacher: {
                type: String,
                default: '',
            },
            itinerary: {
                type: Array,
                default: [],
            },
        },
        methods: {

        },
        data() {
            return {
                mailLink: "mailto:test@test.com?subject=Error%20en%20la%20tarea%20".concat("tareaID")

            }
        },
    }
</script>
