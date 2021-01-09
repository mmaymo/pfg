<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight px-8 py-4">
                Crear curso
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <create-course-form/>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import CreateCourseForm from './CreateCourseForm'
    import JetSectionBorder from './../../Jetstream/SectionBorder'
    import CourseMemberManager from './CourseMemberManager'

    export default {
        props: ['course'],

        components: {
            AppLayout,
            CreateCourseForm,
            JetSectionBorder,
            CourseMemberManager
        },
    }
</script>
