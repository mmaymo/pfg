<template>
    <draggable tag="ul" :list="tasks" :group="{name: 'g1'}" :animation="200" ghost-class="moving-card"
               filter=".action-button"
               class="space-y-6" @change="">
        <li class="flex items-center justify-between" v-for="chapter in tasks"
            :key="chapter.id">
            <div>
                <div class="flex items-center">
                    <span class="ml-4">{{ chapter.name }}</span>
                </div>
                <div class="flex items-center">
                    <!-- Edit task -->
                    <button class="ml-2 text-sm text-gray-400 underline"
                            @click="">
                        Editar
                    </button>

                    <!-- Remove Task -->
                    <button
                            class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                            @click="">
                        Eliminar
                    </button>
                </div>
            </div>
            <nested-draggable :tasks="chapter.tasks"></nested-draggable>

        </li>
    </draggable>
</template>
<script>
    import Draggable from 'vuedraggable'

    export default {
        name: 'nested-draggable',
        components: {Draggable},
        props: {
            tasks: {}
        }
    }
</script>
