<template>
    <jet-form-section @submitted="updateCourseDetails">
        <template #title>
            Detalles del curso editado
        </template>

        <template #description>
            Grado, Semestre y foto del curso.
        </template>

        <template #form>
            <!-- Degree  -->
            <div class="col-span-6">
                <jet-label for="degree" value="Grado al que pertenece el curso" />

                <jet-input id="degree"
                           type="text"
                           class="mt-1 block w-full"
                           v-model="form.degree"
                           :disabled="! permissions.canUpdateTeam" />

                <jet-input-error :message="form.error('degree')" class="mt-2" />
            </div>

            <!-- Semester -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="semester" value="Semestre" />

                <jet-input id="semester"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.semester"
                            :disabled="! permissions.canUpdateTeam" />

                <jet-input-error :message="form.error('semester')" class="mt-2" />
            </div>

            <!-- Pic -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="pic" value="Imagen para el curso" />

                <jet-input id="pic"
                           type="text"
                           class="mt-1 block w-full"
                           v-model="form.pic"
                           :disabled="! permissions.canUpdateTeam" />

                <jet-input-error :message="form.error('pic')" class="mt-2" />
            </div>
            <!-- Tasks -->
            <div class="col-span-6 sm:col-span-4">
                <nav-link :href="route('courses.tasks.index', {'course':course.id})" >
                    Tareas del curso
                </nav-link>
            </div>
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
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import NavLink from "../../Jetstream/NavLink";

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            NavLink
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
