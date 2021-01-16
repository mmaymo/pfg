<template>
    <jet-action-section>
        <template #title>
            Eliminar todos los alumnos
        </template>

        <template #description>
            Borre definitivamente a todos los alumnos.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Esta acción no puede revertirse. Asegúrese de haber descargado cualquier información que quiera conservar.
            </div>

            <div class="mt-5">
                <jet-danger-button @click.native="confirmTeamDeletion">
                    Borrar todos los alumnos
                </jet-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <jet-confirmation-modal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
                <template #title>
                    Borrar todos los alumnos
                </template>

                <template #content>
                    ¿Está seguro de querer borrar a todos los alumnos?.
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingTeamDeletion = false">
                        Cerrar sin borrar
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Borrar todos los alumnos
                    </jet-danger-button>
                </template>
            </jet-confirmation-modal>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionSection from './../../Jetstream/ActionSection'
    import JetButton from './../../Jetstream/Button'
    import JetConfirmationModal from './../../Jetstream/ConfirmationModal'
    import JetDangerButton from './../../Jetstream/DangerButton'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'

    export default {
        props: ['courseId'],

        components: {
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetDangerButton,
            JetSecondaryButton,
        },

        data() {
            return {
                confirmingTeamDeletion: false,
                deleting: false,

                form: this.$inertia.form({
                    //
                }, {
                    bag: 'deleteCourse'
                })
            }
        },

        methods: {
            confirmTeamDeletion() {
                this.confirmingTeamDeletion = true
            },

            deleteTeam() {
                this.form.delete(route('deleteAllMembers', this.courseId), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
