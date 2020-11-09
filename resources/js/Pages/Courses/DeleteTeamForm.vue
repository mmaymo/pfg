<template>
    <jet-action-section>
        <template #title>
            Eliminar curso
        </template>

        <template #description>
            Borre definitivamente este curso y todos sus contenidos.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Una vez un curso es eliminado, se borrarán tambien todos los recursos asociados. Antes de borrarlo, asegúrese de haber descargado cualquier información de este curso que quiera conservar.
            </div>

            <div class="mt-5">
                <jet-danger-button @click.native="confirmTeamDeletion">
                    Borrar curso
                </jet-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <jet-confirmation-modal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
                <template #title>
                    Borrar curso
                </template>

                <template #content>
                    ¿Está seguro de querer borrar este curso? Una vez un curso es eliminado, se borrarán tambien todos los recursos asociados.
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingTeamDeletion = false">
                        Cerrar sin borrar
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Borrar curso
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
        props: ['team'],

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
                    bag: 'deleteTeam'
                })
            }
        },

        methods: {
            confirmTeamDeletion() {
                this.confirmingTeamDeletion = true
            },

            deleteTeam() {
                this.form.delete(route('courses.destroy', this.team), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
