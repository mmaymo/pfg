<template>
    <draggable tag="ul" :list="tasks" :group="{name: 'g1'}" :animation="200" ghost-class="moving-card"
               filter=".action-button"
               class="space-y-6" @change="">
        <li class="flex items-center justify-between bg-gray-100 border-2 border-green-300 rounded p-4" v-for="item in tasks"
            :key="item.id">
            <div class="">
                <div class="mb-2">{{ item.type }}</div>
                <div class="mb-2">{{ item.name }}</div>
                <!-- Edit task -->
                <jet-button>
                    <a :href="route('courses.tasks.edit', {'course':courseId, 'task':item.id})">Editar</a>
                </jet-button>

                <!-- Remove Task -->
                <danger-button
                    @click.native="confirmTaskRemoval(item.id)">
                    Eliminar
                </danger-button>
                <jet-confirmation-modal :show="taskBeingRemoved" @close="taskBeingRemoved = null">
                    <template #title>
                        Borrar Tarea
                    </template>

                    <template #content>
                        ¿Está seguro de querer borrar esta tarea?
                    </template>

                    <template #footer>
                        <jet-secondary-button @click.native="taskBeingRemoved = null">
                            Cerrar sin borrar
                        </jet-secondary-button>

                        <jet-danger-button class="ml-2" @click.native="deleteTask" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Borrar tarea
                        </jet-danger-button>
                    </template>
                </jet-confirmation-modal>
            </div>
            <nested-draggable :tasks="item.tasks" :courseId="courseId"></nested-draggable>
        </li>
    </draggable>
</template>
<script>
    import Draggable from 'vuedraggable'
    import JetButton from "../Jetstream/Button";
    import DangerButton from "../Jetstream/DangerButton";
    import JetSectionBorder from "../Jetstream/SectionBorder";
    import JetConfirmationModal from '../Jetstream/ConfirmationModal'
    import JetDangerButton from "../Jetstream/DangerButton";
    import JetSecondaryButton from "../Jetstream/SecondaryButton";

    export default {
        name: 'nested-draggable',
        components: {Draggable, JetButton, DangerButton, JetSectionBorder, JetConfirmationModal, JetDangerButton,
            JetSecondaryButton,},
        data() {
            return {

                taskBeingRemoved: null,
                deleting: false,

                form: this.$inertia.form({
                    //
                }, {
                    bag: 'deleteTask'
                })
            }
        },
        props: {
            tasks: {},
            courseId: Number,
        },
        methods:{

            confirmTaskRemoval(teamMember) {
                this.taskBeingRemoved = teamMember
            },

            deleteTask() {
                this.form.delete(route('courses.tasks.destroy', {
                    'course': this.courseId,
                    'task': this.taskBeingRemoved
                }), {
                    preserveScroll: true,
                    preserveState: true,
                }).then(() => {
                    this.taskBeingRemoved = null
                })
            },
        }

    }
</script>
