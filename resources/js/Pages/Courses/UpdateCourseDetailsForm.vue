<template>
    <jet-form-section @submitted="updateCourseDetails">
        <template #title>
            Detalles del curso editado
        </template>

        <template #description>
            Grado, Semestre y foto del curso.
        </template>
        <template #form>
        <details-course-fields :course="course" :form="form" :permissions="permissions"/>
        </template>

        <template #actions v-if="permissions.canUpdateTeam">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Salvado.
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
    import DetailsCourseFields from "./DetailsCourseFields";

    export default {
        components: {
            DetailsCourseFields,
            JetActionMessage,
            JetButton,
            JetFormSection
        },

        props: ['team','course', 'permissions'],

        data() {
            return {
                form: this.$inertia.form({
                    degree: this.course.degree,
                    semester: this.course.semester,
                    pic: this.course.pic
                }, {
                    bag: 'updateCourseDetails',
                    resetOnSuccess: false,
                })
            }
        },

        methods: {
            updateCourseDetails() {
                this.form.put(route('courses.update', this.team), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
