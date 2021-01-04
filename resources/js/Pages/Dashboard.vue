<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <div id="mainContentWrap"
                     class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5 z-0 mt-16">
                    <div id="mainContent" class="grid grid-cols-3 gap-4">
                        <div v-if="$page.isTeacher">
                            <ul v-for="course in $page.ownedCourses">
                                <li>
                                    {{course.name}}
                                    <jet-button><a :href="route('courses.show', {'course':course.id})">Editar</a></jet-button>
                                    <jet-button><a :href="route('courses.tasks.show', {'course':course.id, 'task':Object.keys(course.positionArray)[0]})">Ver</a></jet-button>
                                </li>
                            </ul>
                            <div><jet-button><a :href="route('courses.create')">Crear Curso</a></jet-button></div>
                        </div>
                        <div v-else-if="$page.isEnrolled">
                            <ul v-for="course in $page.enrolledCourses">
                                <li>
                                    {{course.name}}

                                    <jet-button><a :href="route('courses.tasks.show', {'course':course.id, 'task':Object.keys(course.positionArray)[0]})">Ver</a></jet-button>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <p>Todavía no ha sido inscrito a ningún curso</p>
                            <p>Enlace de contacto</p>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'
import JetButton from '../Jetstream/Button'

export default {
    components: {
        AppLayout,
        JetButton,
    },

    data() {

        return {}
    },

}
</script>
