<template>
    <div>
        <div v-if="tasks.length > 0">
            <jet-section-border/>
            <!-- Manage Team Members -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Tareas del curso
                </template>

                <template #description>
                    todas las tareas dadas de alta para este curso
                </template>

                <!-- Team Member List -->
                <template #content>

                    <nested-draggable :tasks="tasks" :courseId="courseId"/>
                    <jet-button @click.native="persistContentPositions">
                        Guardar este orden
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

                <jet-danger-button class="ml-2" @click.native="removeTeamMember"
                                   :class="{ 'opacity-25': removeTeamMemberForm.processing }"
                                   :disabled="removeTeamMemberForm.processing">
                    Sí, elimínala
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
        <jet-section-border/>
        <jet-action-section class="mt-10 sm:mt-0">
            <template #title>
                Añadir nuevas tareas
            </template>

            <template #description>
                Las tareas pueden ser de 4 tipos
            </template>

            <!-- Team Member List -->
            <template #content>
                <jet-button>
                    <a :href="route('courses.tasks.create', {'course':courseId})">Añadir</a>
                </jet-button>
            </template>
        </jet-action-section>
        <!-- <div >
             <jet-section-border />

             <jet-form-section @submitted="addTeamMember">
                 <template #title>
                     Añadir Tarea
                 </template>

                 <template #description>
                     Añadir una nueva tarea a este cuso
                 </template>

                 <template #form>
                     <div class="col-span-6">
                         <div class="max-w-xl text-sm text-gray-600">
                             Introduzca el contenido que desea añadir
                         </div>
                     </div>
                     <div class="col-span-6">
                         <button @click="changeTaskType('Document')">Documento</button>
                         <button @click="changeTaskType('Code')">Código</button>
                         <button @click="changeTaskType('Quiz')">Test</button>
                         <button @click="changeTaskType('Card')">Flashcards</button>
                     </div>
                     <div v-if="type === 'Document'">
                         <div class="col-span-6 sm:col-span-4">
                             <jet-label for="name" value="Nombre" />
                             <jet-input id="name" type="text" class="mt-1 block w-full" v-model="addTaskDocForm.name" />
                             <jet-input-error :message="addTaskDocForm.error('name')" class="mt-2" />
                         </div>
                         <div class="col-span-6 sm:col-span-4">
                             <jet-label for="points" value="Puntos" />
                             <jet-input id="points" type="number" class="mt-1 block w-full" v-model="addTaskDocForm.points" />
                             <jet-input-error :message="addTaskDocForm.error('name')" class="mt-2" />
                         </div>
                         <div class="col-span-6 sm:col-span-4">
                             <jet-label for="properties" value="Contenido" />
                             <jet-input id="properties" type="text" class="mt-1 block w-full" v-model="addTaskDocForm.properties" />
                             <jet-input-error :message="addTaskDocForm.error('properties')" class="mt-2" />
                         </div>
                     </div>


                 </template>

                 <template #actions>
                     <jet-action-message :on="addTaskDocForm.recentlySuccessful" class="mr-3">
                         Añadido.
                     </jet-action-message>

                     <jet-button :class="{ 'opacity-25': addTaskDocForm.processing }" :disabled="addTaskDocForm.processing">
                         Añadir
                     </jet-button>
                 </template>
             </jet-form-section>
         </div>-->
    </div>
</template>

<script>
    import JetButton from "../../Jetstream/Button";
    import JetDangerButton from "../../Jetstream/DangerButton";
    import JetConfirmationModal from "../../Jetstream/ConfirmationModal";
    import JetSecondaryButton from "../../Jetstream/SecondaryButton";
    import JetSectionBorder from "../../Jetstream/SectionBorder";
    import JetActionSection from "../../Jetstream/ActionSection";
    import NestedDraggable from "./NestedDraggable";


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
            removeTeamMember() {
                this.removeTeamMemberForm.delete(route('courses.tasks.destroy', {
                    'course': this.course.id,
                    'task': this.teamMemberBeingRemoved
                }), {
                    preserveScroll: true,
                    preserveState: true,
                }).then(() => {
                    this.teamMemberBeingRemoved = null
                })
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
