<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <div id="mainContentWrap"
                     class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
                    <div id="mainContent" class="grid grid-cols-3 gap-4">

                        <div v-for="course in this.courses" class="pt-4">
                            <div class="border-4 m-4 p-4">
                                <nav-link v-if="course.activeTaskId !== null" :href="route('courses.tasks.show', {'course':course.id, 'task':course.activeTaskId})">
                                    {{ course.name }}
                                </nav-link>
                                <div v-else>
                                    <h2>{{ course.name }}</h2>
                                    <nav-link :href="route('courses.show', {'course':course.id})">
                                         Este curso no contiene ninguna tarea
                                    </nav-link>
                                </div>

                                <p>Profesor: {{ course.owner }}</p>
                                <p>Progreso: {{course.progress.position}}/{{course.progress.total}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import NavLink from "../../Jetstream/NavLink";

export default {
    props:[
        'courses'
    ],
    components: {
        AppLayout,
        NavLink,
    },

    data() {
        return {}
    },

}
</script>
