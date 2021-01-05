<template>
    <div class="grid grid-cols-3 gap-4">
        <!-- Degree  -->
        <div>
            <jet-label for="degree" value="Grado al que pertenece el curso"/>

            <jet-input id="degree"
                       type="text"
                       class="mt-1 block w-full"
                       v-model="form.degree"/>

            <jet-input-error :message="form.error('degree')" class="mt-2"/>
        </div>

        <!-- Semester -->
        <div >
            <jet-label for="semester" value="Semestre en que se imparte"/>

            <jet-input id="semester"
                       type="text"
                       class="mt-1 block w-full"
                       v-model="form.semester"/>

            <jet-input-error :message="form.error('semester')" class="mt-2"/>
        </div>

        <!-- Pic -->
        <div>
            <jet-label for="pic" value="Imagen para el curso"/>

            <jet-input id="pic"
                       type="text"
                       class="mt-1 block w-full"
                       v-model="form.pic"/>

            <jet-input-error :message="form.error('pic')" class="mt-2"/>
        </div>
    </div>
</template>
<script>
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import NavLink from "../../Jetstream/NavLink"

    export default {
        name: 'details-course-fields',
        components: {JetInput, JetInputError, JetLabel, NavLink},
        props: {
            form: {type: Object}
        }
    }
</script>
