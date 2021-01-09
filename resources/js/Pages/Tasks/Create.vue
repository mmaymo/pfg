<template>
    <app-layout>
        <template #header>
            <h2 class="font-sans font-semibold text-xl text-gray-800 leading-tight px-8 py-4">
                Crear tarea para {{courseName}}
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
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
