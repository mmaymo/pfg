<template>
    <app-layout>
        <template #header>

        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <h2 class="font-sans font-semibold text-xl text-gray-800 leading-tight">
                    Editar Curso
                </h2>
                <jet-section-title>
                    <template #title>Información básica</template>
                    <template #description></template>
                </jet-section-title>
                <update-course-form :course="course.courseDetails" />

                <task-index  :tasks="course.tasks" :courseId="course.courseDetails.id"/>
                <jet-section-border />
                <course-member-manager class="mt-10 sm:mt-0"
                            :students="course.students" :courseId="course.courseDetails.id"/>
                <template>
                    <jet-section-border />

                    <delete-team-form class="mt-10 sm:mt-0" :courseId="course.courseDetails.id" />
                </template>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import CourseMemberManager from './CourseMemberManager'
    import AppLayout from './../../Layouts/AppLayout'
    import DeleteTeamForm from './DeleteTeamForm'
    import JetSectionBorder from './../../Jetstream/SectionBorder'
    import UpdateCourseForm from './UpdateCourseForm'
    import PageSeparator from '../../Ui/PageSeparator.vue'
    import TaskIndex from "../Tasks/Index";
    import JetSectionTitle from "../../Jetstream/SectionTitle";

    export default {
        props: [
            'course'
        ],

        components: {
            AppLayout,
            DeleteTeamForm,
            JetSectionBorder,
            CourseMemberManager,
            UpdateCourseForm,
            PageSeparator,
            TaskIndex,
            JetSectionTitle,
        },
    }
</script>
