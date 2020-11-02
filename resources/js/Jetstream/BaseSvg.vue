<template>
    <svg xmlns="http://www.w3.org/2000/svg"
         :width="width"
         :height="height"
         viewBox="0 0 20 20"
         :aria-labelledby="iconName"
         role="presentation"
    >
        <title :id="iconName" lang="en">{{ iconName }} icon</title>
        <g :fill="iconColor">
            <path fill-rule="evenodd" :d="d" clip-rule="evenodd" />
        </g>
    </svg>
</template>

<script>

    export default {
        props: {
            iconName: {
                type: String,
                default: 'document'
            },
            width: {
                type: [Number, String],
                default: 18
            },
            height: {
                type: [Number, String],
                default: 18
            },
            d:{
                type:String,
                default: ""
            },
            iconColor: {
                type: String,
                default: 'currentColor'
            }
        }
    }
</script>
