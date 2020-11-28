<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <div id="mainContentWrap"
                     class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
                    <div id="mainContent" class="grid grid-cols-3 gap-4">

                        <div v-for="course in this.courses" class="pt-4">
                            <div class="border-4 m-4 p-4">
                                <nav-link :href="route('courses.tasks.show', {'course':course.id, 'task':8})" :active="$page.currentRouteName == 'course'">
                                    {{ course.name }}
                                </nav-link>
                                <p>Profesor: {{ course.owner.name }}</p>
                                <p>Progreso: {{course.pivot.progress}}</p>
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
