<template>
    <draggable tag="ul" :list="tasks" :group="{name: 'g1'}" :animation="200" ghost-class="moving-card"
               filter=".action-button"
               class="space-y-6" @change="">
        <li class="flex items-center justify-between bg-gray-100 border-2 border-green-300 rounded p-4" v-for="item in tasks"
            :key="item.id">
            <div class="">
                <span>{{ item.name }}</span>
                <!-- Edit task -->
                <jet-button>
                    <a :href="route('courses.tasks.edit', {'course':courseId, 'task':item.id})">Editar</a>
                </jet-button>

                <!-- Remove Task -->
                <danger-button
                    @click="">
                    Eliminar
                </danger-button>
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

    export default {
        name: 'nested-draggable',
        components: {Draggable, JetButton, DangerButton, JetSectionBorder,},
        props: {
            tasks: {},
            courseId: Number,
        },
        methods:{
            linkToEdit(){}
        }

    }
</script>
