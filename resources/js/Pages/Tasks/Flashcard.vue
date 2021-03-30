<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <course-sidebar :courseId="this.courseDetails.id"
                                :courseName="this.courseDetails.name"
                                :teacher="this.courseDetails.teacher"
                                :itinerary="this.tasks" :allowedIds="this.allowedIds"
                                :coursePoints="coursePoints"
                                :courseProgress="courseProgress"></course-sidebar>
                <div id="mainContentWrap"
                     class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0">
                    <div id="mainContent">
                        <div id="task" class="grid grid-column-1">
                            <h1 class="capitalize font-bold text-left px-8 py-4">
                                Tarjetas de estudio</h1>
                        </div>
                        <section v-for="card in cards" class="container bg-gray-200 mb-4">
                            <div class="p-4 text-center font-bold font-xl">
                                <p>{{ card.properties.card.front }}</p>
                            </div>

                            <div class="p-4 bg-white grid grid-cols-5 shadow">
                                <button
                                    class="col-start-3 inline-flex items-center mx-0.5 px-4 py-2 bg-green-300 border border-transparent rounded-md font-semibold text-xs text-green-800 hover:text-white uppercase tracking-widest hover:bg-green-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                    v-on:click="toggle(card)">Ver Respuesta</button>
                            </div>
                            <p v-if="open === card.id" class="p-4">
                                {{ card.properties.card.back }}
                            </p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import CourseSidebar from "../../Components/CourseSidebar";
import JetButton from "../../Jetstream/Button";


export default {
    components: {
        AppLayout,
        CourseSidebar,
        JetButton
    },
    props: [
        'courseDetails',
        'tasks',
        'allowedIds',
        'cards',
        'coursePoints',
        'courseProgress'
    ],
    data() {
        return {
            open: false,
        }
    },
    methods: {
         toggle(card) {
            if(this.open === card.id) {
                this.open = false;
            } else {
                this.open = card.id;
            }
        },
    }
}
</script>
