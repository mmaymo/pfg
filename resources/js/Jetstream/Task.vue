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
                                {{ this.task.chapter }}</h1>
                        </div>
                        <h2 class="group flex whitespace-pre-wrap relative capitalize">
                            {{ this.task.name }}</h2>
                    </div>

                    <text-task :textContent="this.task.content"></text-task>
                    <div id="bottomTaskButtons"
                         class="flex border-b border-gray-300 p-8 pt-16">
                        <div class="w-2/4">
                            <button
                                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                <a :href="route('courses.tasks.show', {'course':courseId, 'task':previousTask})">Anterior</a>
                            </button>
                            <p>Task name anterior</p>
                        </div>
                        <div class="w-2/4 text-right">
                            <button
                                class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                <a :href="route('courses.tasks.show', {'course':courseId, 'task':nextTask})">Sieguiente</a>
                            </button>
                            <p>Task name siguiente</p>
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

export default {
    components: {
        Quiz,
        TextTask,

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
        currentIndex() {
            let positions = this.task.orderedIds;
            let currentPosition = this.task.id;
            return this.getKeyByValue(positions, currentPosition)
        },
        getKeyByValue(object, value) {
            return parseInt(Object.keys(object).find(key => object[key] === value))
        },
        getNextTask(){
            let nextIndex= this.currentIndex() + 1;
            let arr = this.task.orderedIds;
            if(arr.length < nextIndex){
                return false;
            }
            return arr[nextIndex];
        },
        getPrevTask(){
            let prevIndex= this.currentIndex() -1;
            let arr = this.task.orderedIds;
            if(prevIndex < 0){
                return false;
            }
            return arr[prevIndex];
        }

    },
    data() {
        return {
            nextTask: this.getNextTask(),
            previousTask: this.getPrevTask(),
            mailLink: "mailto:test@test.com?subject=Error%20en%20la%20tarea%20".concat("tareaID")

        }
    },

}
</script>
