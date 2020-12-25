<template>
    <div>
        <div>
            <!-- Add Team Member -->
            <jet-form-section @submitted="addTeamMember">
                <template #title>
                    Añadir Alumno
                </template>

                <template #description>
                    Añadir un alumno a este curso
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Introduzca el email de la persona que desee añadir. El email debe estar ya asociado a una cuenta.
                        </div>
                    </div>

                    <!-- Member Email -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="email" value="Email" />
                        <jet-input id="email" type="text" class="mt-1 block w-full" v-model="addTeamMemberForm.email" />
                        <jet-input-error :message="addTeamMemberForm.error('email')" class="mt-2" />
                    </div>

                    <!-- Role -->
                   <!-- <div class="col-span-6 lg:col-span-4" v-if="availableRoles.length > 0">
                        <jet-label for="roles" value="Role" />
                        <jet-input-error :message="addTeamMemberForm.error('role')" class="mt-2" />

                        <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            <div class="px-4 py-3"
                                            :class="{'border-t border-gray-200': i > 0}"
                                            @click="addTeamMemberForm.role = role.key"
                                            v-for="(role, i) in availableRoles"
                                            :key="i"
                                            >
                                <div :class="{'opacity-50': addTeamMemberForm.role && addTeamMemberForm.role != role.key}">
                                    &lt;!&ndash; Role Name &ndash;&gt;
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600" :class="{'font-semibold': addTeamMemberForm.role == role.key}">
                                            {{ role.name }}
                                        </div>

                                        <svg v-if="addTeamMemberForm.role == role.key" class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>

                                    &lt;!&ndash; Role Description &ndash;&gt;
                                    <div class="mt-2 text-xs text-gray-600">
                                        {{ role.description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
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

        <div v-if="students.length > 0">
            <jet-section-border />

            <!-- Manage Team Members -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Alumnos registrados
                </template>

                <template #description>
                    todos los alumnos registrados en el curso
                </template>

                <!-- Team Member List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="user in students" :key="user.id">
                            <div class="flex items-center">

                                <div class="ml-4">{{ user.name }}</div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-4">{{ user.points }} Puntos</div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-4">Progreso: {{ user.progress }}%</div>
                            </div>

                            <div class="flex items-center">
                                <!-- Manage Team Member Role -->
                                <!--<button class="ml-2 text-sm text-gray-400 underline"
                                        v-if="userPermissions.canAddTeamMembers && availableRoles.length"
                                        @click="manageRole(user)">
                                    {{ displayableRole(user.membership.role) }}
                                </button>

                                <div class="ml-2 text-sm text-gray-400" v-else-if="availableRoles.length">
                                    {{ displayableRole(user.membership.role) }}
                                </div>-->

                                <!-- Leave Team -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                    @click="confirmLeavingTeam"
                                                    v-if="$page.user.id === user.id">
                                    Abandonar curso
                                </button>

                                <!-- Remove Team Member -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                    @click="confirmTeamMemberRemoval(user.id)">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>

        <!-- Role Management Modal -->
        <!--<jet-dialog-modal :show="currentlyManagingRole" @close="currentlyManagingRole = false">
            <template #title>
                Gestión de permisos
            </template>

            <template #content>
                <div v-if="managingRoleFor">
                    <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                        <div class="px-4 py-3"
                                        :class="{'border-t border-gray-200': i > 0}"
                                        @click="updateRoleForm.role = role.key"
                                        v-for="(role, i) in availableRoles"
                                        :key="i"
                                        >
                            <div :class="{'opacity-50': updateRoleForm.role && updateRoleForm.role != role.key}">
                                &lt;!&ndash; Role Name &ndash;&gt;
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600" :class="{'font-semibold': updateRoleForm.role == role.key}">
                                        {{ role.name }}
                                    </div>

                                    <svg v-if="updateRoleForm.role == role.key" class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>

                                &lt;!&ndash; Role Description &ndash;&gt;
                                <div class="mt-2 text-xs text-gray-600">
                                    {{ role.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="currentlyManagingRole = false">
                    Cerrar sin cambios
                </jet-secondary-button>

                <jet-button class="ml-2" @click.native="updateRole" :class="{ 'opacity-25': updateRoleForm.processing }" :disabled="updateRoleForm.processing">
                    Guardar
                </jet-button>
            </template>
        </jet-dialog-modal>-->

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

    export default {
        components: {
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
            'courseId'
        ],

        data() {
            return {
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

                currentlyManagingRole: false,
                managingRoleFor: null,
                confirmingLeavingTeam: false,
                teamMemberBeingRemoved: null,
            }
        },

        methods: {
            addTeamMember() {
                this.addTeamMemberForm.post(route('courses.users.store', this.courseId), {
                    preserveScroll: true
                });
            },

            manageRole(teamMember) {
                this.managingRoleFor = teamMember
                this.updateRoleForm.role = teamMember.membership.role
                this.currentlyManagingRole = true
            },

            updateRole() {
                this.updateRoleForm.put(route('team-members.update', [this.team, this.managingRoleFor]), {
                    preserveScroll: true,
                }).then(() => {
                    this.currentlyManagingRole = false
                })
            },

            confirmLeavingTeam() {
                this.confirmingLeavingTeam = true
            },

            leaveTeam() {
                this.leaveTeamForm.delete(route('courses.users.destroy', [this.courseId, this.$page.user.id]))
            },

            confirmTeamMemberRemoval(teamMember) {
                this.teamMemberBeingRemoved = teamMember
            },

            removeTeamMember() {
                this.removeTeamMemberForm.delete(route('courses.users.destroy', [this.courseId, this.teamMemberBeingRemoved]), {
                    preserveScroll: true,
                    preserveState: true,
                }).then(() => {
                    this.teamMemberBeingRemoved = null
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
