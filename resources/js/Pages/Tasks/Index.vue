<template>
    <div>
        <div v-if="tasks.length > 0">
            <jet-section-border/>

            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Tareas del curso
                </template>

                <template #description>
                </template>

                <template #content>
                    <nested-draggable :tasks="tasks" :courseId="courseId"/>
                </template>
                <template #actions>
                    <jet-button @click.native="persistContentPositions">
                        Guardar este orden
                    </jet-button>
                    <jet-button>
                        <a :href="route('courses.tasks.create', {'course':courseId})">Añadir Nueva Tarea</a>
                    </jet-button>
                </template>
            </jet-action-section>

        </div>
        <div v-else>
            <jet-section-border/>
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Añadir tarea
                </template>
                <template #description>
                </template>
                <!-- Team Member List -->
                <template #content>
                    <jet-button>
                        <a :href="route('courses.tasks.create', {'course':courseId})">Añadir Nueva Tarea</a>
                    </jet-button>
                </template>
            </jet-action-section>
        </div>
        <!-- Remove Team Member Confirmation Modal -->
        <jet-confirmation-modal :show="teamMemberBeingRemoved" @close="teamMemberBeingRemoved = null">
            <template #title>
                Eliminar tarea
            </template>

            <template #content>
                ¿Está seguro de que quiere eliminar esta tarea del curso?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="teamMemberBeingRemoved = null">
                    Cerrar sin borrar
                </jet-secondary-button>

                <jet-danger-button id="" class="ml-2" @click.native="removeTeamMember"
                                   :class="{ 'opacity-25': removeTeamMemberForm.processing }"
                                   :disabled="removeTeamMemberForm.processing">
                    Sí, elimínala
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
    import JetButton from "../../Jetstream/Button";
    import JetDangerButton from "../../Jetstream/DangerButton";
    import JetConfirmationModal from "../../Jetstream/ConfirmationModal";
    import JetSecondaryButton from "../../Jetstream/SecondaryButton";
    import JetSectionBorder from "../../Jetstream/SectionBorder";
    import JetActionSection from "../../Jetstream/ActionSection";
    import NestedDraggable from "../../Components/NestedDraggable";


    export default {
        props: [
            'tasks', 'courseId'
        ],
        components: {
            NestedDraggable,
            JetButton,
            JetConfirmationModal,
            JetDangerButton,
            JetSecondaryButton,
            JetSectionBorder,
            JetActionSection
        },

        data() {

            return {
                updateOrderForm: this.$inertia.form({
                    orderedContentIds: this.tasks
                }, {
                    bag: 'updateOrder',
                    resetOnSuccess: true,
                }),
                removeTeamMemberForm: this.$inertia.form({
                    //
                }, {
                    bag: 'removeTeamMember',
                }),
                teamMemberBeingRemoved: null,
            }
        },
        methods: {
            addTeamMember() {
                this.addTeamMemberForm.post(route('courses.tasks.store', {'course': this.course.id}), {
                    preserveScroll: true
                });
            },
            confirmTeamMemberRemoval(teamMember) {
                this.teamMemberBeingRemoved = teamMember
            },
            showEdit(taskId) {
                this.taskEdited = taskId
            },
            persistContentPositions(){
                this.updateOrderForm.post(route('updateOrderContent', {'course': this.courseId}), {
                    preserveScroll: true
                });
            }
        }

    }
</script>
