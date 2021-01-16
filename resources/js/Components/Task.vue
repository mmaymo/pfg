<template>
    <div id="mainContentWrap"
         class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0">
        <div id="mainContent">
            <div id="task" class="grid grid-column-1">
                <h1 class="capitalize font-bold text-left px-8 py-4">
                    {{ this.task.name }}</h1>

                    <quiz-task v-if="this.task.type == 'quiz'" :quiz="this.task.contents.quiz" :courseId="this.courseId" :taskId="this.task.id"/>
                    <code-task v-if="this.task.type == 'code'" :textContent="this.task.contents"/>
                    <card-task v-if="this.task.type == 'card'" :textContent="this.task.contents"/>
                   <text-task v-if="this.task.type == 'document'" :textContent="this.task.contents"></text-task>

                    <div id="bottomTaskButtons"
                         class="flex border-b border-gray-300 p-8 pt-16">
                        <div class="w-2/4">
                            <jet-button>
                                <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.previousId})">Anterior</a>
                            </jet-button>
                        </div>
                        <div class="w-2/4 text-right">
                            <div v-if="this.taskCompleted">
                                <jet-button>
                                    <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.nextId})">Siguiente</a>
                                </jet-button>
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
</template>

<script>


import QuizTask from "./QuizTask";
import TextTask from "./TextTask";
import CodeTask from "./CodeTask";
import CardTask from "./CardTask";
import JetButton from "../Jetstream/Button";

export default {
    components: {
        QuizTask,
        TextTask,
        CodeTask,
        CardTask,
        JetButton,
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
