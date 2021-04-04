<template>
    <div id="mainContentWrap"
         class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0">
        <div id="mainContent">
            <div id="task" class="grid grid-column-1">
                <h1 class="capitalize font-bold text-left px-8 py-4">
                    {{ this.task.name }}</h1>

                <quiz-task v-if="this.task.type === 'quiz'" :quiz="this.task.contents.quiz"
                           :courseId="this.courseId" :task="this.task"/>
                <multiple-quiz-task v-if="this.task.type === 'multipleQuiz'"
                                    :quiz="this.task.contents.quiz" :courseId="this.courseId"
                                    :task="this.task"/>

                <code-task v-if="this.task.type === 'code'" :textContent="this.task.contents" :courseId="this.courseId" :task="this.task"/>
                <card-task v-if="this.task.type === 'card'" :textContent="this.task.contents"/>
                <text-task v-if="this.task.type === 'document'" :textContent="this.task.contents"></text-task>

                    <div id="bottomTaskButtons"
                         class="flex border-b border-gray-300 p-8 pt-16">

                        <div class="w-2/4">
                            <jet-button>
                                <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.previousId})">Anterior</a>
                            </jet-button>
                        </div>
                        <div v-if="message">{{message}}</div>
                        <div class="w-2/4 text-right">

                            <jet-button v-if="!this.task.isDone" @click.native="addPoints">

                                Suma y Sigue

                            </jet-button>
                            <jet-button v-if="this.task.isDone">
                                <a :href="route('courses.tasks.show', {'course':courseId, 'task':task.nextId})">Siguiente</a>
                            </jet-button>
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
import MultipleQuizTask from "./MultipleQuizTask";
import TextTask from "./TextTask";
import CodeTask from "./CodeTask";
import CardTask from "./CardTask";
import JetButton from "../Jetstream/Button";

export default {
    components: {
        QuizTask,
        MultipleQuizTask,
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
            this.message = 'Para poder avanzar debes completar antes la tarea'
            if (this.task.type === 'document' || this.task.type === 'card' || this.task.type === 'chapter') {
                console.log('aui')
                this.form.post('addDone', this.courseId);
                this.message = '';
            }
        },

    },
    updated(){
        this.form.nextId = this.task.nextId
        this.form.taskId = this.task.id
    },
    data() {
        return {
            form: this.$inertia.form({
                nextId: this.task.nextId, taskId: this.task.id
            }, {
                bag: 'default',
                resetOnSuccess: true,
            }),
            message: '',
            mailLink: "mailto:test@test.com?subject=Error%20en%20la%20tarea%20".concat(this.task.id)

        }
    },

}
</script>
