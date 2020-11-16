<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <div id="sidebar"
                     class="fixed inset-0 h-full bg-white z-90 w-full border-b -mb-16 lg:-mb-0 lg:static lg:h-auto lg:overflow-y-visible lg:border-b-0 lg:pt-0 lg:w-1/4 lg:block lg:border-0 xl:w-1/5 hidden pt-16">
                    <div id="navWrapper"
                         class="h-full overflow-y-auto scrolling-touch lg:h-auto lg:block lg:relative lg:sticky lg:bg-transparent overflow-hidden lg:top-16 bg-white">
                        <div class="w-full text-right mt-8 pr-4 font-bold">{{this.course}}</div>
                        <div class="w-full text-right mb-4 pr-4 font-light">{{this.teacher}}</div>
                        <nav id="nav" class="px-6 pt-6 overflow-y-auto text-base lg:text-sm lg:py-12 lg:pl-6 lg:pr-8 sticky?lg:h-(screen-16)">
                            <div v-for="task in this.itinerary" class="mb-10">
                                <a href="#"
                                   class="flex items-center px-2 -mx-2 py-1 hover:text-gray-900 font-medium text-green-700">
                                    <base-svg :icon-name=task.type :width=20 :height=20 :d=iconType(task.type) ></base-svg>
                                    <span class="ml-3">{{task.name}}</span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>
                <div id="mainContentWrap" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
                    <div id="mainContent">
                       <text-task></text-task>
                    </div>
                </div>


            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'
import Quiz from '../Jetstream/QuizTask'
import TextTask from '../Jetstream/TextTask'
import BaseSvg from "../Jetstream/BaseSvg";


export default {
    components: {
        AppLayout,
        Quiz,
        TextTask,
        BaseSvg
    },
    data() {
        return {
            course: "Nombre del Capítulo",
            teacher: "Nombre profesor",
            itinerary: [
                {name: "Task name 1", type:"document"},
                {name: "Task name 2", type: "quiz"},
                {name: "Task name 3", type: "code"},
                {name: "Task name 4", type: "card"},
            ]
        }
    },
    methods: {

        iconType(type){
            switch (type){
                case "document": return "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z";
                case "quiz": return "M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z";
                case "code": return "M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z";
                case "card": return "M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z";
            }

        },

    },
}
</script>
