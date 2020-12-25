<template>
    <app-layout>


        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Curso
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <page-separator :title="'Información básica'" />
                <update-course-form :course="course.courseDetails" />
                <jet-section-border />

               <!-- <course-member-manager class="mt-10 sm:mt-0"
                            :team="team"
                            :course-points="coursePoints"
                            :course-progress="courseProgress"
                            :available-roles="availableRoles"
                            :user-permissions="permissions" />

                <template v-if="permissions.canDeleteTeam && ! team.personal_team">
                    <jet-section-border />

                    <delete-team-form class="mt-10 sm:mt-0" :team="team" />
                </template>-->
            </div>
        </div>
    </app-layout>
</template>

<script>
    import CourseMemberManager from './CourseMemberManager'
    import AppLayout from './../../Layouts/AppLayout'
    import DeleteTeamForm from './DeleteTeamForm'
    import JetSectionBorder from './../../Jetstream/SectionBorder'
    import UpdateCourseForm from './UpdateCourseForm'
    import PageSeparator from '../../Ui/PageSeparator.vue'

    export default {
        props: [
            'course',
        ],

        components: {
            AppLayout,
            DeleteTeamForm,
            JetSectionBorder,
            CourseMemberManager,
            UpdateCourseForm,
            PageSeparator
        },
    }
</script>
