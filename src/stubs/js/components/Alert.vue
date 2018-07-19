<template>
    <ui-modal dismiss-on="close-button esc"
              size="medium"
              ref="alertModal"
              title="Dcity"
    >{{ message}}
    </ui-modal>
</template>
<script>
  export default {
    props: {
      message: {
        type: String,
        require: true
      }
    },
    mounted() {
      this.$refs.alertModal.open();
    }
  };
</script>