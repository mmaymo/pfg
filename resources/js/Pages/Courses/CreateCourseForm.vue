<template>
    <jet-form-section @submitted="createCourse">
        <template #title>
            Detalles del curso
        </template>

        <template #description>
            Crea un nuevo curso
        </template>

        <template #form>
            <courseNameFields :form="form"/>
            <details-course-fields :form="form" />

        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Guardado.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Guardar
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetButton from './../../Jetstream/Button'
    import JetFormSection from './../../Jetstream/FormSection'
    import CourseNameFields from "./CourseNameFields";
    import DetailsCourseFields from "./DetailsCourseFields";

    export default {
        components: {
            CourseNameFields,
            JetActionMessage,
            JetButton,
            JetFormSection,
            DetailsCourseFields

        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    degree:'',
                    semester:'',
                    pic:''
                }, {
                    bag: 'createCourse',
                    resetOnSuccess: false,
                })
            }
        },

        methods: {
            createCourse() {
                console.log(this.form)
                this.form.post(route('courses.store'), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
