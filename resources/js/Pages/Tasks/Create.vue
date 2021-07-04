<template>
    <app-layout>
        <template #header>

        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <h2 class="editHeader">
                Crear tarea para {{courseName}}
            </h2>
            <document-task-form :courseId="courseId" :chapters="chapters" :availableTypes="availableTypes"/>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import DocumentTaskForm from './DocumentTaskForm'
    import JetSectionBorder from './../../Jetstream/SectionBorder'

    export default {
        props: ['courseName', 'courseId', 'chapters', 'availableTypes'],

        components: {
            AppLayout,
            DocumentTaskForm,
            JetSectionBorder,
        },
    }
</script>
