<template>
    <div id="mainContentWrap"
         class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
        <div id="mainContent">
            <div id="task" class="flex">
                <div class="pb-4 w-full pt-8 lg:pt-8">
                    <div
                        class="max-w-3xl mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:px-12 xl:w-3/4 ">
                        <h1 class="group flex whitespace-pre-wrap relative capitalize">
                            {{ this.task.name }}</h1>
                    </div>
                    <card-task :textContent="this.task.contents"/>
                   <!--<text-task :textContent="this.task.contents"></text-task>-->
                    <button v-on:click="addPoints"
                        class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                        Completada
                    </button>
                    <div id="bottomTaskButtons"
                         class="flex border-b border-gray-300 p-8 pt-16">
                        <div class="w-2/4">
                            <button
                                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.previousId})">Anterior</a>
                            </button>
                        </div>
                        <div class="w-2/4 text-right">
                            <div v-if="this.taskCompleted">
                                <button
                                    class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                    <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.nextId})">Siguiente</a>
                                </button>
                            </div>

                        </div>

                    </div>
                    <div id="footer" class="pt-8">
                        <div class="text-right w-full pr-8"><a :href="this.mailLink" target="_blank"
                                                               rel="noopener noreferrer">Informa de
                            un problema en la tarea</a></div>
                        <div class="text-right w-full pr-8"><a href="http://portal.uned.es/"
                                                               target="_blank">Dudas</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>


import Quiz from "./QuizTask";
import TextTask from "./TextTask";
import CodeTask from "./CodeTask";
import CardTask from "./CardTask";

export default {
    components: {
        Quiz,
        TextTask,
        CodeTask,
        CardTask
    },
    props: {
        courseId: {
            type: Number,
            default: '',
        },
        task: {
            type: Object,
            default: []
        },
    },
    methods: {
        addPoints(){
//boton que envia a post puntos y recibe un ok
        this.taskCompleted = true;

        },

    },
    data() {
        return {
//esto tiene que venir de arriba, si ya esta en allowed entonces esto está a true
            taskCompleted: true,
            mailLink: "mailto:test@test.com?subject=Error%20en%20la%20tarea%20".concat("tareaID")

        }
    },

}
</script>
