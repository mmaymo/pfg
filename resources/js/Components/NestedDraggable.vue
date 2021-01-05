<template>
    <draggable tag="ul" :list="tasks" :group="{name: 'g1'}" :animation="200" ghost-class="moving-card"
               filter=".action-button"
               class="space-y-6" @change="">
        <li class="flex items-center justify-between" v-for="item in tasks"
            :key="item.id">
            <div>
                <div class="flex items-center">
                    <span class="ml-4">{{ item.name }}</span>
                </div>
                <div class="flex items-center">
                    <!-- Edit task -->
                    <jet-button>
                        <a :href="route('courses.tasks.edit', {'course':courseId, 'task':item.id})">Editar</a>
                    </jet-button>

                    <!-- Remove Task -->
                    <button
                            class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                            @click="">
                        Eliminar
                    </button>
                </div>
            </div>
            <nested-draggable :tasks="item.tasks" :courseId="courseId"></nested-draggable>

        </li>
    </draggable>
</template>
<script>
    import Draggable from 'vuedraggable'
    import JetButton from "../Jetstream/Button";

    export default {
        name: 'nested-draggable',
        components: {Draggable, JetButton},
        props: {
            tasks: {},
            courseId: Number,
        },
        methods:{
            linkToEdit(){}
        }

    }
</script>
