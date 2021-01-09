<template>
    <app-layout>
        <template #header>
            <h2 class="font-sans font-semibold text-xl text-gray-800 leading-tight px-8 py-4">
                Editar tarea para {{courseName}}
            </h2>
        </template>

        <div>
            <div class="">
                <update-task-form :courseId="courseId" :chapters="chapters" :availableTypes="availableTypes" :task="task"/>
            </div>
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
