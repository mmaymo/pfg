<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <course-sidebar :courseId="this.courseId" :courseName="this.courseName" :teacher="this.teacher"
                                :itinerary="this.tasks" :allowedIds="this.allowedIds"></course-sidebar>
                <task :courseId="this.courseId" :task="this.task" ></task>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import CourseSidebar from "../../Jetstream/CourseSidebar";
import Task from "../../Jetstream/Task"

export default {
    components: {
        AppLayout,
        Task,
        CourseSidebar
    },
    props: [
        'teacher',
        'courseName',
        'courseId',
        'courseProgress',
        'coursePoints',
        'tasks',
        'task',
        'allowedIds'
    ],
    data() {
        return {

        }
    },

}
</script>
