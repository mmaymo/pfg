<template>
    <app-layout>
        <template #header>

        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <h2 class="editHeader">
                Editar tarea para {{courseName}}
            </h2>
            <update-task-form :courseId="courseId" :chapters="chapters" :availableTypes="availableTypes" :task="task"/>
        </div>

    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import JetSectionBorder from './../../Jetstream/SectionBorder'
    import UpdateTaskForm from './UpdateTaskForm'

    export default {
        props: ['courseName', 'courseId', 'chapters', 'availableTypes', 'task'],

        components: {
            AppLayout,
            UpdateTaskForm,
            JetSectionBorder,
        },
    }
</script>
