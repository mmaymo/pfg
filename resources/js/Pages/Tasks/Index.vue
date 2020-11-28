<template>
    <app-layout>


        <div v-if="tasks.length > 0">
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
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="task in tasks" :key="task.id">
                            <div class="flex items-center" v-if="taskEdited === task.id">
                                <update-task-form :permissions="userPermissions" :task="task" :course="course.id"></update-task-form>

                                <jet-secondary-button @click.native="taskEdited = null">
                                    Cerrar
                                </jet-secondary-button>
                            </div>
                            <div v-else><div class="flex items-center">
                                <div class="ml-4">{{ task.name }}</div>
                            </div>

                                <div class="flex items-center">
                                    <!-- Edit task -->
                                    <button class="ml-2 text-sm text-gray-400 underline"
                                            v-if="userPermissions.canAddTeamMembers"
                                            @click="showEdit(task.id)">
                                        Editar todavía no está
                                    </button>

                                    <!-- Remove Task -->
                                    <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                            @click="confirmTeamMemberRemoval(task.id)"
                                            v-if="userPermissions.canRemoveTeamMembers">
                                        Eliminar
                                    </button>
                                </div></div>
                        </div>
                    </div>
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

                <jet-danger-button class="ml-2" @click.native="removeTeamMember" :class="{ 'opacity-25': removeTeamMemberForm.processing }" :disabled="removeTeamMemberForm.processing">
                    Sí, elimínala
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
        <div v-if="userPermissions.canAddTeamMembers">
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
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="name" value="Nombre" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="addTeamMemberForm.name" />
                        <jet-input-error :message="addTeamMemberForm.error('name')" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="points" value="Puntos" />
                        <jet-input id="points" type="number" class="mt-1 block w-full" v-model="addTeamMemberForm.points" />
                        <jet-input-error :message="addTeamMemberForm.error('name')" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="type" value="Tipo" />
                        <select id="type"  class="mt-1 block w-full rounded-md shadow-sm" v-model="addTeamMemberForm.type">
                            <option value="document">Texto</option>
                            <option value="card">Tarjeta</option>
                            <option value="quiz">Test</option>
                            <option value="code">Codigo</option>
                        </select>
                        <jet-input-error :message="addTeamMemberForm.error('name')" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="properties" value="Contenido" />
                        <jet-input id="properties" type="text" class="mt-1 block w-full" v-model="addTeamMemberForm.properties" />
                        <jet-input-error :message="addTeamMemberForm.error('properties')" class="mt-2" />
                    </div>

                </template>

                <template #actions>
                    <jet-action-message :on="addTeamMemberForm.recentlySuccessful" class="mr-3">
                        Añadido.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': addTeamMemberForm.processing }" :disabled="addTeamMemberForm.processing">
                        Añadir
                    </jet-button>
                </template>
            </jet-form-section>
        </div>


    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import NavLink from "../../Jetstream/NavLink";
import JetButton from "../../Jetstream/Button";
import JetDangerButton from "../../Jetstream/DangerButton";
import JetActionMessage from "../../Jetstream/ActionMessage";
import JetActionSection from "../../Jetstream/ActionSection";
import JetConfirmationModal from "../../Jetstream/ConfirmationModal";
import JetDialogModal from "../../Jetstream/DialogModal";
import JetFormSection from "../../Jetstream/FormSection";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetLabel from "../../Jetstream/Label";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";
import JetSectionBorder from "../../Jetstream/SectionBorder";
import UpdateTaskForm from "./UpdateTaskForm";

export default {
    props:[
        'tasks',
        'course',
        'userPermissions'
    ],
    components: {
        AppLayout,
        NavLink,
        JetActionMessage,
        JetActionSection,
        JetButton,
        JetConfirmationModal,
        JetDangerButton,
        JetDialogModal,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        JetSectionBorder,
        UpdateTaskForm
    },

    data() {

        return {
            addTeamMemberForm: this.$inertia.form({
                name:'',
                type:'',
                position:150,
                points:'',
                properties: ''
            }, {
                bag: 'addTeamMember',
                resetOnSuccess: true,
            }),
            removeTeamMemberForm: this.$inertia.form({
                //
            }, {
                bag: 'removeTeamMember',
            }),
            teamMemberBeingRemoved: null,
            taskEdited: null
        }
    },
    methods:{
        addTeamMember() {
            this.addTeamMemberForm.post(route('courses.tasks.store', {'course':this.course.id}), {
                preserveScroll: true
            });
        },
        confirmTeamMemberRemoval(teamMember) {
            this.teamMemberBeingRemoved = teamMember
        },
        removeTeamMember() {
            this.removeTeamMemberForm.delete(route('courses.tasks.destroy', {'course':this.course.id, 'task':this.teamMemberBeingRemoved}), {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.teamMemberBeingRemoved = null
            })
        },
        showEdit(taskId) {
            this.taskEdited = taskId
        },
    }

}
</script>
