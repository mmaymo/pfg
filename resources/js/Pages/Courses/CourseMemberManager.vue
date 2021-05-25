<template>
    <div>
        <div>
            <!-- Register Member -->
            <jet-form-section @submitted="registerMail">
                <template #title>
                    Registrar alumno
                </template>

                <template #description>
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Introduzca el email de la persona que desee añadir.
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="register" value="Registrar email" />
                        <jet-input id="register" type="text" class="mt-1 block w-full" v-model="registerMailForm.email" autofocus/>
                        <jet-input-error :message="registerMailForm.error('email')" class="mt-2"/>
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="registerMailForm.recentlySuccessful" class="mr-3">
                        Añadido.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': registerMailForm.processing }" :disabled="registerMailForm.processing">
                        Añadir
                    </jet-button>
                </template>
            </jet-form-section>
            <jet-form-section @submitted="registerMailTeacher">
                <template #title>
                    Registrar profesor
                </template>

                <template #description>
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Introduzca el email de la persona que desee añadir.
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="registerTeacher" value="Registrar email" />
                        <jet-input id="registerTeacher" type="text" class="mt-1 block w-full" v-model="registerMailTeacherForm.email" autofocus/>
                        <jet-input-error :message="registerMailTeacherForm.error('email')" class="mt-2"/>
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="registerMailTeacherForm.recentlySuccessful" class="mr-3">
                        Añadido.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': registerMailTeacherForm.processing }" :disabled="registerMailTeacherForm.processing">
                        Añadir
                    </jet-button>
                </template>
            </jet-form-section>
            <!-- Add Team Member -->
            <jet-form-section @submitted="addTeamMember">
                <template #title>
                    Añadir Alumno
                </template>

                <template #description>
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Seleccione entre los usuarios dados de alta.
                        </div>
                    </div>
                    <!-- Member Email -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="email" value="Email" />
                        <select class="mt-1 block w-full" v-if="userList.length > 0" id="email" v-model="addTeamMemberForm.email">
                            <option v-for="user in userList" :value="user.email">{{user.email}} - - {{user.name}}</option>
                        </select>
                        <jet-input-error :message="addTeamMemberForm.error('email')" class="mt-2" />
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
            <jet-form-section @submitted="addTeamMemberBatch">
                <template #title>
                    Añadir Alumnos en bloque
                </template>

                <template #description>
                </template>

                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="studentsFile" value="Archivo de alumnos" />
                        <form
                            @submit.prevent="addTeamMemberBatch"
                            method="post"
                            enctype="multipart/form-data">
                            <jet-label for="file" value="El archivo tiene que ser de tipo csv y con nombre, apellido, mail"/>
                            <input type="file" ref="studentFile" class="mt-1 block w-full" @change="updateTestFile"/>
                            <br/>
                            <jet-button> Guardar archivo</jet-button>
                        </form>
                    </div>
                </template>

            </jet-form-section>
        </div>

        <div v-if="students.length > 0">
            <jet-section-border />

            <!-- Manage Team Members -->
            <jet-action-section class="">
                <template #title>
                    Alumnos registrados
                </template>

                <template #description>
                </template>

                <!-- Team Member List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="user in students" :key="user.id">
                            <div class="flex items-center">
                                <div class="ml-4">{{ user.name }}</div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-4">{{ user.email }}</div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-4">{{ user.points }} Puntos</div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-4">Progreso: {{ user.progress.toFixed(1) }}%</div>
                            </div>

                            <div class="flex items-center">

                                <!-- abandonar curso -->
                                <jet-button @click="confirmLeavingTeam" v-if="$page.user.id === user.id">
                                    Abandonar curso
                                </jet-button>

                                <!-- Eliminar alumno del curso -->
                                <danger-button @click.native="confirmTeamMemberRemoval(user.id)">
                                    Eliminar
                                </danger-button>
                               <danger-button @click.native="confirmResetValues(user.id)">
                                    Resetear valores
                                </danger-button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>

        <!-- Leave Team Confirmation Modal -->
        <jet-confirmation-modal :show="confirmingLeavingTeam" @close="confirmingLeavingTeam = false">
            <template #title>
                Dejar curso
            </template>

            <template #content>
                ¿Está seguro de que quiere abandonar este curso?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="confirmingLeavingTeam = false">
                    Cerrar sin cambios
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="leaveTeam" :class="{ 'opacity-25': leaveTeamForm.processing }" :disabled="leaveTeamForm.processing">
                    Abandonar curso
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>

        <!-- Remove Team Member Confirmation Modal -->
        <jet-confirmation-modal :show="teamMemberBeingRemoved" @close="teamMemberBeingRemoved = null">
            <template #title>
                Eliminar alumno
            </template>

            <template #content>
                ¿Está seguro de que quiere eliminar a este alumno del curso?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="teamMemberBeingRemoved = null">
                    Cerrar sin guardar
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="removeTeamMember" :class="{ 'opacity-25': removeTeamMemberForm.processing }" :disabled="removeTeamMemberForm.processing">
                    Eliminar
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
        <jet-confirmation-modal :show="resetingValues" @close="resetingValues = null">
            <template #title>
                Resetear valores
            </template>

            <template #content>
                ¿Está seguro de que quiere borrar los puntos y el progreso de este alumno?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="resetingValues = null">
                    Cerrar sin guardar
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="resetValues" :class="{ 'opacity-25': resetValuesForm.processing }" :disabled="resetValuesForm.processing">
                    Eliminar
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetActionSection from './../../Jetstream/ActionSection'
    import JetButton from './../../Jetstream/Button'
    import JetConfirmationModal from './../../Jetstream/ConfirmationModal'
    import JetDangerButton from './../../Jetstream/DangerButton'
    import JetDialogModal from './../../Jetstream/DialogModal'
    import JetFormSection from './../../Jetstream/FormSection'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'
    import JetSectionBorder from './../../Jetstream/SectionBorder'
    import DangerButton from "../../Jetstream/DangerButton";

    export default {
        components: {
            DangerButton,
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
        },

        props: [
            'students',
            'courseId',
            'userList'
        ],

        data() {
            return {
                registerStudents: this.$inertia.form({
                    file: ''
                }, {
                    bag: 'addCourseMember',
                    resetOnSuccess: true,
                }),
                registerMailForm: this.$inertia.form({
                    email: ''
                }, {
                    bag: 'addCourseMember',
                    resetOnSuccess: true,
                }),
                registerMailTeacherForm: this.$inertia.form({
                    email: ''
                }, {
                    bag: 'addCourseTeacherMember',
                    resetOnSuccess: true,
                }),
                addTeamMemberForm: this.$inertia.form({
                    email: '',
                    role: null,
                }, {
                    bag: 'addCourseMember',
                    resetOnSuccess: true,
                }),

                updateRoleForm: this.$inertia.form({
                    role: null,
                }, {
                    bag: 'updateRole',
                    resetOnSuccess: false,
                }),

                leaveTeamForm: this.$inertia.form({
                    //
                }, {
                    bag: 'leaveTeam',
                }),

                removeTeamMemberForm: this.$inertia.form({
                    //
                }, {
                    bag: 'removeTeamMember',
                }),
                resetValuesForm: this.$inertia.form({
                    resetValues:true
                }, {
                    bag: 'resetValues',
                }),

                currentlyManagingRole: false,
                managingRoleFor: null,
                confirmingLeavingTeam: false,
                teamMemberBeingRemoved: null,
                resetingValues: null,
                studentFile:null
            }
        },

        methods: {
            updateTestFile() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.studentFile = e.target.result;

                };

                reader.readAsDataURL(this.$refs.studentFile.files[0]);
            },
            registerMail() {
                this.registerMailForm.post(route('courses.users.store', this.courseId), {
                    preserveScroll: true
                });
            },
            registerMailTeacher() {
                this.registerMailTeacherForm.post(route('addTeacher', this.courseId), {
                    preserveScroll: true
                });
            },
            addTeamMember() {
                this.addTeamMemberForm.post(route('courses.users.store', this.courseId), {
                    preserveScroll: true
                });
            },
            addTeamMemberBatch() {
                if (this.$refs.studentFile) {
                    this.registerStudents.file = this.$refs.studentFile.files[0]
                }
                this.registerStudents.post(route('courses.users.store', this.courseId), {
                    preserveScroll: true
                });
            },

            confirmLeavingTeam() {
                this.confirmingLeavingTeam = true
            },

            leaveTeam() {
                this.leaveTeamForm.delete(route('courses.users.destroy', [this.courseId, this.$page.user.id]))
            },

            confirmTeamMemberRemoval(teamMember) {
                console.log('elimina')
                this.teamMemberBeingRemoved = teamMember
            },
            confirmResetValues(teamMember) {
                console.log('reset')
                this.resetingValues = teamMember
            },

            removeTeamMember() {
                this.removeTeamMemberForm.delete(route('courses.users.destroy', [this.courseId, this.teamMemberBeingRemoved]), {
                    preserveScroll: true,
                    preserveState: true,
                }).then(() => {
                    this.teamMemberBeingRemoved = null
                })
            },
            resetValues() {
                this.resetValuesForm.put(route('courses.users.update', [this.courseId, this.resetingValues]), {
                    preserveScroll: true,
                    preserveState: true,
                }).then(() => {
                    this.resetingValues = null
                })
            },

            displayableRole(role) {
                return this.availableRoles.find(r => r.key == role).name
            },

            userPoints(userId){
                let points = 0;
                this.coursePoints.forEach(function (user) {
                    if (user.id === userId){
                       points = user.pivot.points;
                    }
                });
                return points;
            },

            userProgress(userId){
                let progress = 0;
                this.courseProgress.forEach(function (user) {
                    if (user.id === userId){
                        progress = user.pivot.progress;
                    }
                });
                return progress;
            }
        },
    }
</script>
