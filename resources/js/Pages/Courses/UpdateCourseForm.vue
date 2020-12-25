<template>
    <jet-form-section @submitted="updateTeamName">

        <template #form>
            <courseNameFields :form="form"/>
            <details-course-fields :form="form"/>
        </template>

        <template #actions >
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
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
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import CourseNameFields from "./CourseNameFields"
    import DetailsCourseFields from "./DetailsCourseFields";

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            CourseNameFields,
            DetailsCourseFields
        },

        props: ['course'],

        data() {
            return {
                form: this.$inertia.form({
                    name: this.course.name,
                    degree:this.course.degree,
                    semester:this.course.semester,
                    pic:this.course.pic
                }, {
                    bag: 'updateCourseMain',
                    resetOnSuccess: false,
                })
            }
        },

        methods: {
            updateTeamName() {
                this.form.put(route('courses.update', {'course': this.course.id}), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
