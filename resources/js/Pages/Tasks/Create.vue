<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear tarea para {{courseName}}
            </h2>
        </template>

        <div>
            <div class="">
                <document-task-form :courseId="courseId"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import DocumentTaskForm from './DocumentTaskForm'
    import JetSectionBorder from './../../Jetstream/SectionBorder'

    export default {
        props: ['courseName', 'courseId'],

        components: {
            AppLayout,
            DocumentTaskForm,
            JetSectionBorder,
        },
    }
</script>
