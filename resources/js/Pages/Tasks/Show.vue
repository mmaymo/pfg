<template>
    <app-layout>
        <div class="w-full max-w-screen-xl mx-auto px-6">
            <div class="lg:flex -mx-6">
                <course-sidebar :courseId="this.courseDetails.id" :courseName="this.courseDetails.name" :teacher="this.courseDetails.teacher"
                                :itinerary="this.tasks" :allowedIds="this.allowedIds" :coursePoints="coursePoints" :courseProgress="courseProgress"></course-sidebar>
                <task :courseId="this.courseDetails.id" :task="this.task" ></task>
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
        'courseDetails',
        'tasks',
        'allowedIds',
        'task',
        'coursePoints',
        'courseProgress', 'index'
    ]
}
</script>
