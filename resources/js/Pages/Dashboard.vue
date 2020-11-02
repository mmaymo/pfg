<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <div id="sidebar"
                     class="fixed inset-0 h-full bg-white z-90 w-full border-b -mb-16 lg:-mb-0 lg:static lg:h-auto lg:overflow-y-visible lg:border-b-0 lg:pt-0 lg:w-1/4 lg:block lg:border-0 xl:w-1/5 hidden pt-16">
                    <div id="navWrapper"
                         class="h-full overflow-y-auto scrolling-touch lg:h-auto lg:block lg:relative lg:sticky lg:bg-transparent overflow-hidden lg:top-16 bg-white">
                        <div>{{this.course}}</div>
                        <div>{{this.teacher}}</div>
                        <nav id="nav" class="px-6 pt-6 overflow-y-auto text-base lg:text-sm lg:py-12 lg:pl-6 lg:pr-8 sticky?lg:h-(screen-16)">
                            <div v-for="task in this.itinerary" class="mb-10">
                                <a href="#"
                                   class="flex items-center px-2 -mx-2 py-1 hover:text-gray-900 font-medium text-gray-900">

                                    <span class="ml-3">{{task.name}}</span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>
                <div id="mainContentWrap" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">
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


export default {
    components: {
        AppLayout,
        Quiz,
        TextTask
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
    template:'<div>DEsde aQui</div>',
    methods: {
        //convertir a component el svg
        iconType(type){
            switch (type){
                case "document": return ;
                case "quiz": return;
                case "code": return;
                case "card": return;
            }

        },

    },
}
</script>
