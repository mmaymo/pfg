<template>
  <div
    class="page-separator">
    <div
      class="page-separator__text"
      >
      <slot>{{ title }}</slot>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      title: {
        type: String,
        default: 'Page Separator Title'
      },
      center: {
        type: Boolean
      },
      textClass: {
        type: String,
        default: null
      }
    }
  }
</script>
